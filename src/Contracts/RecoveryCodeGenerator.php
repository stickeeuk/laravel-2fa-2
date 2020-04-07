<?php

namespace Stickee\Laravel2fa\Contracts;

interface RecoveryCodeGenerator
{
    /**
     * Generate recovery codes
     *
     * @param int $count The number of codes to get
     *
     * @return array
     */
    public function get(int $count): array;

    /**
     * Generate a recovery code
     *
     * @return string
     */
    public function getOne(): string;
}
