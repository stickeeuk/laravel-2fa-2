<?php

namespace Stickee\Laravel2fa\Services;

use BaconQrCode\Renderer\Image\ImageBackEndInterface;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Stickee\Laravel2fa\Contracts\QrCodeGenerator as QrCodeGeneratorInterface;

class BaconQrCodeGenerator implements QrCodeGeneratorInterface
{
    /**
     * Constructor
     *
     * @param \BaconQrCode\Renderer\Image\ImageBackEndInterface $backend The Bacon QR Code backend
     */
    public function __construct(ImageBackEndInterface $backend)
    {
        $this->backend = $backend;
    }

    /**
     * Get a QR code inline (e.g. XML or data URI)
     *
     * @param string $url The URL the QR code will link to
     *
     * @return string
     */
    public function getInline(string $url): string
    {
        $renderer = new ImageRenderer(
            new RendererStyle(config('laravel-2fa.qr_code_size', 400)),
            $this->backend
        );
        $writer = new Writer($renderer);

        $data = $writer->writeString($url, 'utf-8');

        if ($this->backend instanceof ImagickImageBackEnd) {
            return 'data:image/png;base64,' . base64_encode($data);
        }

        return $data;
    }
}
