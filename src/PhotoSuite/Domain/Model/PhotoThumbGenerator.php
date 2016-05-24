<?php

namespace PHPhotoSuit\PhotoSuite\Domain\Model;

use PHPhotoSuit\PhotoSuite\Domain\HttpUrl;

interface PhotoThumbGenerator
{
    /**
     * @param ThumbId $thumbId
     * @param Photo $photo
     * @param PhotoThumbSize $thumbSize
     * @param HttpUrl $thumbHttpUrl
     * @return PhotoThumb
     */
    public function generate(ThumbId $thumbId, Photo $photo, PhotoThumbSize $thumbSize, HttpUrl $thumbHttpUrl);

    /**
     * @return PhotoFormat
     */
    public function conversionFormat();
}
