<?php

namespace PHPhotoSuit\PhotoSuite\Domain;

use PHPhotoSuit\PhotoSuite\Domain\Exception\InvalidLanguageException;

class Lang
{
    /** @var string */
    private $value;

    const LANGUAGE_EN = 'EN';
    const LANGUAGE_ES = 'ES';

    /**
     * @param $value
     * @throws InvalidLanguageException
     */
    public function __construct($value)
    {
        if (!defined('static::LANGUAGE_' . strtoupper($value))) {
            throw new InvalidLanguageException('invalid language: ' . $value);
        }
        $this->value = strtoupper($value);
    }

    /**
     * @return string
     */
    public function value()
    {
        return $this->value;
    }
}
