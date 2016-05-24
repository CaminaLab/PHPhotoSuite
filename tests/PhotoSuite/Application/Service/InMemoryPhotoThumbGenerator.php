<?php

namespace PHPhotoSuit\Tests\PhotoSuite\Application\Service;

use PHPhotoSuit\PhotoSuite\Domain\HttpUrl;
use PHPhotoSuit\PhotoSuite\Domain\Model\Photo;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoFormat;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoId;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoThumb;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoThumbGenerator;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoThumbMode;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoThumbSize;
use PHPhotoSuit\PhotoSuite\Domain\Model\ThumbId;

class InMemoryPhotoThumbGenerator implements PhotoThumbGenerator
{

    /**
     * @param Photo $photo
     * @param PhotoThumbSize $thumbSize
     * @param PhotoThumbMode $thumbMode
     * @param HttpUrl $thumbHttpUrl
     * @return PhotoThumb
     */
    public function generate(Photo $photo, PhotoThumbSize $thumbSize, PhotoThumbMode $thumbMode, HttpUrl $thumbHttpUrl)
    {
        return new PhotoThumb(
            new ThumbId(),
            new PhotoId($photo->id()),
            $thumbHttpUrl,
            $thumbSize,
            $thumbMode
        );
    }

    /**
     * @return PhotoFormat
     */
    public function conversionFormat()
    {
        return new PhotoFormat(PhotoFormat::FORMAT_JPEG);
    }
}
