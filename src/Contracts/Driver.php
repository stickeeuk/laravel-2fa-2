<?php

namespace Stickee\Laravel2fa\Contracts;

interface Driver
{
    /**
     * Verify a code is correct
     *
     * @param string $code The code
     * @param null|int $newerThan The minimum timestamp, to prevent reuse
     *
     * @return bool
     */
    public function verify(string $code, ?int $newerThan = null): bool;

    /**
     * Initialise user data before they register
     */
    public function initialiseUserData(): void;

    /**
     * Reinitialise user data to reset their 2FA
     */
    public function reinitialiseUserData(): void;

    /**
     * Start the authentication process, e.g. send an SMS
     */
    public function startAuthentication(): void;
}
