<?php

namespace Stickee\Laravel2fa\Contracts;

interface StateStore
{
    /**
     * Get data from the store
     *
     * @param string $name The key
     * @param mixed $default The default value
     *
     * @return mixed
     */
    public function get(string $name, $default = null);

    /**
     * Put data into the store
     *
     * @param string $name The key
     * @param mixed $value The value to store
     */
    public function put(string $name, $value): void;

    /**
     * Delete data from the store
     *
     * @param string $name The key
     */
    public function forget(string $name): void;
}
