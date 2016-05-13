<?php

namespace PHPhotoSuit\PhotoSuite\Domain;

use PHPhotoSuit\PhotoSuite\Domain\Exception\InvalidHttpUrl;

class HttpUrl
{
    /** @var string */
    private $value;

    /**
     * @param string $value
     * @throws InvalidHttpUrl
     */
    public function __construct($value)
    {
        if (!preg_match('/(http[s]?:\/\/.+)[\/]?/', $value)) {
            throw new InvalidHttpUrl($value);
        }
        $this->value = $value[strlen($value)-1] === '/' ? substr($value, 0, -1) : $value;
    }

    /**
     * @return string
     */
    public function value()
    {
        return $this->value;
    }
}
