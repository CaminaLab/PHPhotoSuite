<?php

namespace PHPhotoSuit\PhotoSuite\Domain;

use PHPhotoSuit\PhotoSuite\Domain\Exception\InvalidFormatException;

class PhotoFormat
{
    const FORMAT_JPEG = 'jpeg';
    const FORMAT_JPG = 'jpg';
    const FORMAT_PNG = 'png';
    const FORMAT_GIF = 'gif';

    /** @var string */
    private $value;

    /**
     * @param string $value
     * @throws InvalidFormatException
     */
    public function __construct($value)
    {
        if (!defined('static::FORMAT_' . strtoupper($value))) {
            throw new InvalidFormatException('invalid format: ' . $value);
        }
        $this->value = strtolower($value);
    }

    /**
     * @return string
     */
    public function value()
    {
        return $this->value;
    }
}
