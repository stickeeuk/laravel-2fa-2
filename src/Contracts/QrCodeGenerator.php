<?php

namespace Stickee\Laravel2fa\Contracts;

interface QrCodeGenerator
{
    /**
     * Get a QR code inline (e.g. XML or data URI)
     *
     * @param string $url The URL the QR code will link to
     *
     * @return string
     */
    public function getInline(string $url): string;
}
