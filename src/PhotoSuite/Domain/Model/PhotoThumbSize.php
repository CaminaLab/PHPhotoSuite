<?php

namespace PHPhotoSuit\PhotoSuite\Domain\Model;

class PhotoThumbSize
{
    /** @var integer */
    private $height;
    /** @var integer */
    private $width;

    /**
     * PhotoThumbSize constructor.
     * @param int $height
     * @param int $width
     */
    public function __construct($height, $width)
    {
        if (!is_numeric($height) || $height <= 0) {
            throw new \InvalidArgumentException('Invalid height'); 
        }
        if (!is_numeric($width) || $width <= 0) {
            throw new \InvalidArgumentException('Invalid width'); 
        }
        $this->height = $height;
        $this->width = $width;
    }

    /**
     * @return int
     */
    public function height()
    {
        return $this->height;
    }

    /**
     * @return int
     */
    public function width()
    {
        return $this->width;
    }
}
