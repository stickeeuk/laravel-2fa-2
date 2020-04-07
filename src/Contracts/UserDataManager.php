<?php

namespace Stickee\Laravel2fa\Contracts;

interface UserDataManager
{
    /**
     * Get the user's data
     *
     * @return array
     */
    public function get(): array;

    /**
     * Set the user's data
     *
     * @param array $data The user's data
     */
    public function set(array $data): void;

    /**
     * Get a value in the user's data
     *
     * @param string $key The key
     * @param mixed $default The default value
     *
     * @return mixed
     */
    public function getValue($key, $default = null);

    /**
     * Set a value in the user's data
     *
     * @param string $key The key
     * @param string $value The value
     */
    public function setValue($key, $value): void;

    /**
     * Get the user's data for a driver
     *
     * @param string $driverName The driver name
     *
     * @return array
     */
    public function getDriver(string $driverName): array;

    /**
     * Set the user's data for a driver
     *
     * @param string $driverName The driver name
     * @param array $data The user's driver data
     */
    public function setDriver(string $driverName, array $data): void;
}
