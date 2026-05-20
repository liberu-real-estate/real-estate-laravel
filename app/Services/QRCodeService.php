<?php

namespace App\Services;

use App\Models\Property;
use InvalidArgumentException;

class QRCodeService
{
    private const GOOGLE_CHART_BASE = 'https://chart.googleapis.com/chart';

    /**
     * Generate a QR code URL for a property using the Google Chart API.
     *
     * @param  Property  $property
     * @param  int  $size  Size in pixels (width and height)
     * @return string  URL to the QR code image
     */
    public function generatePropertyQRCodeUrl(Property $property, int $size = 200): string
    {
        if ($size < 50 || $size > 1000) {
            throw new InvalidArgumentException('QR code size must be between 50 and 1000 pixels.');
        }

        $propertyUrl = url('/properties/' . $property->id);

        return $this->generateQRCodeUrl($propertyUrl, $size);
    }

    /**
     * Generate a QR code URL for any arbitrary content.
     *
     * @param  string  $content  The content to encode
     * @param  int  $size  Size in pixels
     * @return string  URL to the QR code image
     */
    public function generateQRCodeUrl(string $content, int $size = 200): string
    {
        if (empty($content)) {
            throw new InvalidArgumentException('QR code content cannot be empty.');
        }

        if ($size < 50 || $size > 1000) {
            throw new InvalidArgumentException('QR code size must be between 50 and 1000 pixels.');
        }

        return self::GOOGLE_CHART_BASE . '?' . http_build_query([
            'cht' => 'qr',
            'chs' => "{$size}x{$size}",
            'chl' => $content,
            'choe' => 'UTF-8',
        ]);
    }

    /**
     * Generate QR code data (SVG path elements) using a pure PHP approach.
     * Returns a data URI for embedding directly in HTML.
     *
     * @param  string  $content
     * @param  int  $size
     * @return array  ['url' => string, 'content' => string, 'size' => int]
     */
    public function generateQRCodeData(string $content, int $size = 200): array
    {
        if (empty($content)) {
            throw new InvalidArgumentException('QR code content cannot be empty.');
        }

        return [
            'url' => $this->generateQRCodeUrl($content, $size),
            'content' => $content,
            'size' => $size,
        ];
    }

    /**
     * Generate QR code data for a property, including property details.
     *
     * @param  Property  $property
     * @param  int  $size
     * @return array
     */
    public function generatePropertyQRCodeData(Property $property, int $size = 200): array
    {
        $propertyUrl = url('/properties/' . $property->id);

        return [
            'url' => $this->generateQRCodeUrl($propertyUrl, $size),
            'property_url' => $propertyUrl,
            'property_id' => $property->id,
            'property_title' => $property->title,
            'size' => $size,
        ];
    }
}
