<?php

namespace Stickee\Laravel2fa\Drivers;

use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Route;
use Stickee\Laravel2fa\Contracts\UserDataManager;
use Stickee\Laravel2fa\Http\Controllers\TwilioController;
use Twilio\Rest\Client;

class Twilio extends AbstractDriver
{
    /**
     * The user
     *
     * @var \Illuminate\Foundation\Auth\User $user
     */
    protected $user;

    /**
     * Constructor
     *
     * @param string $name The driver name
     * @param \Stickee\Laravel2fa\Contracts\UserDataManager $userDataManager The user data manager
     * @param \Illuminate\Foundation\Auth\User $user The user
     */
    public function __construct(
        string $name,
        UserDataManager $userDataManager,
        User $user
    ) {
        parent::__construct($name, $userDataManager);

        $this->user = $user;
    }

    public static function boot(string $name)
    {
        $guards = implode(', ', config('laravel-2fa.twilio.guards') ?? ['web']);

        self::registerRoutes($name, function () use ($guards) {
            Route::middleware("auth:$guards")->get('register', TwilioController::class . '@register')->name('register');
            Route::middleware("auth:$guards")->post('send-code', TwilioController::class . '@sendCode')->name('send-code');
            Route::middleware("auth:$guards")->get('enter-code', TwilioController::class . '@enterCode')->name('enter-code');
            Route::middleware("auth:$guards")->post('confirm', TwilioController::class . '@confirm')->name('confirm');
        });
    }

    /**
     * Verify a code is correct
     *
     * @param string $code The code
     * @param null|int $newerThan The minimum timestamp, to prevent reuse
     *
     * @return bool
     */
    public function verify(string $code, ?int $newerThan = null): bool
    {
        $data = $this->getData();
        $secret = (string)($data['secret'] ?? '');

        if (empty($secret)) {
            return false;
        }

        if ($newerThan !== null) {
            // Timestamp must be at a boundary
            if (now()->timestamp > $newerThan) {
                return false;
            }
        }

        $verified = $secret === $code;

        if ($verified) {
            $data['verified_at'] = now()->timestamp;
            $this->setData($data);
        }

        return $verified;
    }

    /**
     * Get data required for the registration view
     *
     * @return array
     */
    public function getRegistrationViewData(): array
    {
        return [];
    }

    /**
     * Reinitialise user data to reset their 2FA
     */
    public function reinitialiseUserData(): void
    {
        $this->setData([
            'verified_at' => null,
        ]);
    }

    /**
     * Start the authentication process, e.g. send an SMS
     */
    public function startAuthentication(): void
    {
        $data = $this->getData();
        $code = $this->generateCode();

        $data['secret'] = $code;
        $data['generated_at'] = now()->timestamp;

        $this->setData($data);

        $client = new Client(config('laravel-2fa.twilio.sid'), config('laravel-2fa.twilio.token'));
        $client->messages->create(
            $data['mobile_number'],
            [
                'from' => $data['from'] ?? config('laravel-2fa.twilio.from'),
                'body' => str_replace('[code]', $code, config('laravel-2fa.twilio.message')),
            ]
        );

        \Log::info($code);
    }

    /**
     * Set the mobile number.
     *
     * @param string $number
     * @return void
     */
    public function setMobileNumber(string $number)
    {
        $data = $this->getData();
        $data['mobile_number'] = $number;
        $this->setData($data);
    }

    /**
     * Generate a token for 2FA validation.
     *
     * @return string
     */
    protected function generateCode()
    {
        return (string)random_int(100000, 999999);
    }
}
