<?php

namespace PHPhotoSuit\PhotoSuite\Application\Service;

use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoThumbMode;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoThumbSize;

class ThumbRequest
{
    /** @var PhotoThumbSize */
    private $thumbSize;

    /**
     * ThumbRequest constructor.
     * @param PhotoThumbSize $thumbSize
     */
    public function __construct(PhotoThumbSize $thumbSize)
    {
        $this->thumbSize = $thumbSize;
    }

    /**
     * @return PhotoThumbSize
     */
    public function thumbSize()
    {
        return $this->thumbSize;
    }
}
