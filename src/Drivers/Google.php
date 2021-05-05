<?php

namespace Stickee\Laravel2fa\Drivers;

use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Route;
use PragmaRX\Google2FA\Google2FA;
use Stickee\Laravel2fa\Contracts\QrCodeGenerator;
use Stickee\Laravel2fa\Http\Controllers\GoogleController;
use Stickee\Laravel2fa\Models\Laravel2fa;

class Google extends AbstractDriver
{
    /**
     * The user
     *
     * @var \Illuminate\Foundation\Auth\User $user
     */
    protected $user;

    /**
     * Google 2FA service
     *
     * @var \PragmaRX\Google2FA\Google2FA $google2fa
     */
    private $google2fa;

    /**
     * QR code generator
     *
     * @var \Stickee\Laravel2fa\Contracts\QrCodeGenerator $qrCodeGenerator
     */
    private $qrCodeGenerator;

    /**
     * Constructor
     *
     * @param string $name The driver name
     * @param \Stickee\Laravel2fa\Models\Laravel2fa $laravel2fa The Laravel2fa instance
     * @param \Illuminate\Foundation\Auth\User $user The user
     * @param \PragmaRX\Google2FA\Google2FA $google2fa Google 2FA service
     * @param \Stickee\Laravel2fa\Contracts\QrCodeGenerator $qrCodeGenerator QR code generator
     */
    public function __construct(
        string $name,
        Laravel2fa $laravel2fa,
        User $user,
        Google2FA $google2fa,
        QrCodeGenerator $qrCodeGenerator
    ) {
        parent::__construct($name, $laravel2fa);

        $this->user = $user;
        $this->google2fa = $google2fa;
        $this->qrCodeGenerator = $qrCodeGenerator;
    }

    public static function boot(string $name)
    {
        self::registerRoutes($name, function () {
            Route::get('register', GoogleController::class . '@register')->name('register');
            Route::post('confirm', GoogleController::class . '@confirm')->name('confirm');
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

        if ($secret === '') {
            return false;
        }

        if ($newerThan !== null) {
            // Timestamp must be at a boundary
            $newerThan = (int)floor($newerThan / $this->google2fa->getKeyRegeneration());
        }

        return $this->google2fa->verify($code, $secret, null, null, $newerThan);
    }

    /**
     * Get data required for the registration view
     *
     * @return array
     */
    public function getRegistrationViewData(): array
    {
        $data = $this->getData();

        $usernameAttribute = 'email';
        $userClass = get_class($this->user);

        foreach (config('laravel-2fa.models') as $model) {
            if (in_array('username_attribute', $model)
                && $model['class'] ?? null === $userClass
            ) {
                $usernameAttribute = $model['username_attribute'];

                break;
            }
        }

        $qrCodeDestinationUrl = $this->google2fa->getQRCodeUrl(
            config('laravel-2fa.app_name') ?? config('app.name'),
            $this->user->$usernameAttribute,
            $data['secret']
        );

        $qrCode = $this->qrCodeGenerator->getInline($qrCodeDestinationUrl);

        return [
            'qrCode' => $qrCode,
            'secret' => $data['secret'],
        ];
    }

    /**
     * Reinitialise user data to reset their 2FA
     */
    public function reinitialiseUserData(): void
    {
        $this->setData([
            'secret' => $this->google2fa->generateSecretKey(),
        ]);
    }

    /**
     * Start the authentication process, e.g. send an SMS
     */
    public function startAuthentication(): void
    {
        // Do nothing
    }
}
