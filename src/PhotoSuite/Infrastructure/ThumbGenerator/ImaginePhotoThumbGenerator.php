<?php

namespace PHPhotoSuit\PhotoSuite\Infrastructure\ThumbGenerator;

use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Image\ImagineInterface;
use Imagine\Image\Point;
use PHPhotoSuit\PhotoSuite\Domain\HttpUrl;
use PHPhotoSuit\PhotoSuite\Domain\Model\Photo;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoFile;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoFormat;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoId;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoThumb;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoThumbGenerator;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoThumbSize;
use PHPhotoSuit\PhotoSuite\Domain\Model\ThumbId;
use PHPhotoSuit\PhotoSuite\Domain\RandomIdGenerator;

class ImaginePhotoThumbGenerator implements PhotoThumbGenerator
{
    const CONVERSION_FORMAT = 'jpeg';
    /** @var ThumbGeneratorConfig */
    private $thumbGeneratorConfig;
    /** @var ImagineInterface */
    private $imagine;

    /**
     * @param ThumbGeneratorConfig $thumbGeneratorConfig
     */
    public function __construct(ThumbGeneratorConfig $thumbGeneratorConfig)
    {
        $this->thumbGeneratorConfig = $thumbGeneratorConfig;
        $this->imagine = new Imagine();
    }

    /**
     * @param ThumbId $thumbId
     * @param Photo $photo
     * @param PhotoThumbSize $thumbSize
     * @param HttpUrl $thumbHttpUrl
     * @return PhotoThumb
     */
    public function generate(ThumbId $thumbId, Photo $photo, PhotoThumbSize $thumbSize, HttpUrl $thumbHttpUrl)
    {
        $photoFile = $photo->photoFile() ? $photo->photoFile() : $this->downloadPhoto($photo->getPhotoHttpUrl());
        $thumbFile = $this->thumbGeneratorConfig->tempPath() . '/' . $thumbId->id() . '.' . self::CONVERSION_FORMAT;
        $target = new Box($thumbSize->width(), $thumbSize->height());
        $originalImage = $this->imagine->open($photoFile->filePath());
        $img = $originalImage->thumbnail($target, ImageInterface::THUMBNAIL_OUTBOUND);
        $img->save($thumbFile);

        return new PhotoThumb(
            $thumbId,
            new PhotoId($photo->id()),
            $thumbHttpUrl,
            $thumbSize,
            new PhotoFile($thumbFile)
        );
    }

    /**
     * @return PhotoFormat
     */
    public function conversionFormat()
    {
        return new PhotoFormat(self::CONVERSION_FORMAT);
    }

    /**
     * @param $photoHttpUrl
     * @return PhotoFile
     */
    private function downloadPhoto($photoHttpUrl)
    {
        $tmpLocation = $this->thumbGeneratorConfig->tempPath() . '/' . md5($photoHttpUrl);
        if (!file_exists($tmpLocation)) {
            $ch = curl_init($photoHttpUrl);
            $fp = fopen($tmpLocation, 'wb');
            curl_setopt($ch, CURLOPT_FILE, $fp);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_exec($ch);
            curl_close($ch);
            fclose($fp);
        }

        return new PhotoFile($tmpLocation);
    }
}
