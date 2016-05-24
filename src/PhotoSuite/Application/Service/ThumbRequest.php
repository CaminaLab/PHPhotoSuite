<?php

namespace PHPhotoSuit\PhotoSuite\Application\Service;

use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoThumbMode;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoThumbSize;

class ThumbRequest
{
    /** @var PhotoThumbSize */
    private $thumbSize;
    /** @var PhotoThumbMode */
    private $thumbMode;

    /**
     * ThumbRequest constructor.
     * @param PhotoThumbSize $thumbSize
     * @param PhotoThumbMode $thumbMode
     */
    public function __construct(PhotoThumbSize $thumbSize, PhotoThumbMode $thumbMode)
    {
        $this->thumbSize = $thumbSize;
        $this->thumbMode = $thumbMode;
    }

    /**
     * @return PhotoThumbSize
     */
    public function thumbSize()
    {
        return $this->thumbSize;
    }

    /**
     * @return PhotoThumbMode
     */
    public function thumbMode()
    {
        return $this->thumbMode;
    }
}
