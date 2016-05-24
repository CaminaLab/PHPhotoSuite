<?php

namespace PHPhotoSuit\PhotoSuite\Domain\Model;

use PHPhotoSuit\PhotoSuite\Domain\HttpUrl;

interface PhotoThumbGenerator
{
    /**
     * @param Photo $photo
     * @param PhotoThumbSize $thumbSize
     * @param PhotoThumbMode $thumbMode
     * @param HttpUrl $thumbHttpUrl
     * @return PhotoThumb
     */
    public function generate(Photo $photo, PhotoThumbSize $thumbSize, PhotoThumbMode $thumbMode, HttpUrl $thumbHttpUrl);

    /**
     * @return PhotoFormat
     */
    public function conversionFormat();
}
