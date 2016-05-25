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
     * @param ThumbId $thumbId
     * @param Photo $photo
     * @param PhotoThumbSize $thumbSize
     * @param HttpUrl $thumbHttpUrl
     * @return PhotoThumb
     */
    public function generate(ThumbId $thumbId, Photo $photo, PhotoThumbSize $thumbSize, HttpUrl $thumbHttpUrl)
    {
        return new PhotoThumb(
            new ThumbId(),
            new PhotoId($photo->id()),
            $thumbHttpUrl,
            $thumbSize,
            $photo->photoFile()
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
