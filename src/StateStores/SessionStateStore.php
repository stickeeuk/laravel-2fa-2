<?php

namespace Stickee\Laravel2fa\StateStores;

use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Session;
use PragmaRX\Google2FA\Google2FA;
use Stickee\Laravel2fa\Contracts\StateStore;

class SessionStateStore implements StateStore
{
    /**
     * Get data from the store
     *
     * @param string $name The key
     * @param mixed $default The default value
     *
     * @return mixed
     */
    public function get(string $name, $default = null)
    {
        return Session::get($this->getName($name), $default);
    }

    /**
     * Put data into the store
     *
     * @param string $name The key
     * @param mixed $value The value to store
     */
    public function put(string $name, $value): void
    {
        Session::put($this->getName($name), $value);
    }

    /**
     * Delete data from the store
     *
     * @param string $name The key
     */
    public function forget(string $name): void
    {
        Session::forget($this->getName($name));
    }

    /**
     * Get the prefixed session key
     *
     * @param string $name The unprefixed key
     *
     * @return string
     */
    private function getName(string $name): string
    {
        return config('state_prefix', 'laravel-2fa') . '.' . $name;
    }
}
