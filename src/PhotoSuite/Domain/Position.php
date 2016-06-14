<?php

namespace PHPhotoSuit\PhotoSuite\Domain;

class Position
{
    /** @var integer */
    private $value;

    /**
     * Position constructor.
     * @param int $value
     */
    public function __construct($value = 9999)
    {
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function value()
    {
        return $this->value;
    }
}
