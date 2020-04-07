<?php

namespace Stickee\Laravel2fa\StateStores;

use Illuminate\Foundation\Auth\User;
use PragmaRX\Google2FA\Google2FA;
use Stickee\Laravel2fa\Contracts\StateStore;

class StatelessStateStore implements StateStore
{
    /**
     * The state
     *
     * @param array $state
     */
    private $state = [];

    /**
     * Get data from the store
     *
     * @param string $name The key
     * @param mixed $default The default value
     *
     * @return mixed
     */
    public function get(string $name, $default)
    {
        return $this->state[$name] ?? $default;
    }

    /**
     * Put data into the store
     *
     * @param string $name The key
     * @param mixed $value The value to store
     */
    public function put(string $name, $value): void
    {
        $this->state[$name] = $value;
    }

    /**
     * Delete data from the store
     *
     * @param string $name The key
     */
    public function forget(string $name): void
    {
        unset($this->state[$name]);
    }
}
