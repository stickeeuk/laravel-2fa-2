<?php

namespace Stickee\Laravel2fa\Services;

use Illuminate\Foundation\Auth\User;
use Stickee\Laravel2fa\Contracts\UserDataManager as UserDataManagerInterface;

class UserDataManager implements UserDataManagerInterface
{
    /**
     * The user
     *
     * @var \Illuminate\Foundation\Auth\User $user The user
     */
    private $user;

    /**
     * Constructor
     *
     * @param \Illuminate\Foundation\Auth\User $user The user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the user's data
     *
     * @return array
     */
    public function get(): array
    {
        if (empty($this->user->laravel2fa_data)) {
            return [];
        }

        return json_decode(decrypt($this->user->laravel2fa_data), true);
    }

    /**
     * Set the user's data
     *
     * @param array $data The user's data
     */
    public function set(array $data): void
    {
        $this->user->laravel2fa_data = encrypt(json_encode($data));
        $this->user->save();
    }

    /**
     * Get a value in the user's data
     *
     * @param string $key The key
     * @param mixed $default The default value
     *
     * @return mixed
     */
    public function getValue($key, $default = null)
    {
        $data = $this->get();

        return array_key_exists($key, $data) ? $data[$key] : $default;
    }

    /**
     * Set a value in the user's data
     *
     * @param string $key The key
     * @param string $value The value
     */
    public function setValue($key, $value): void
    {
        $data = $this->get();
        $data[$key] = $value;

        $this->set($data);
    }

    /**
     * Get the user's data for a driver
     *
     * @param string $driverName The driver name
     *
     * @return array
     */
    public function getDriver(string $driverName): array
    {
        $drivers = $this->getValue('drivers', []);

        return $drivers[$driverName] ?? [];
    }

    /**
     * Set the user's data for a driver
     *
     * @param string $driverName The driver name
     * @param array $data The user's driver data
     */
    public function setDriver(string $driverName, array $data): void
    {
        $drivers = $this->getValue('drivers', []);

        $drivers[$driverName] = $data;

        $this->setValue('drivers', $drivers);
    }
}
