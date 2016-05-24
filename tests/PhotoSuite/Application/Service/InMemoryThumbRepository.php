<?php

namespace PHPhotoSuit\Tests\PhotoSuite\Application\Service;

use PHPhotoSuit\PhotoSuite\Domain\HttpUrl;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoId;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoThumb;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoThumbMode;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoThumbRepository;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoThumbSize;
use PHPhotoSuit\PhotoSuite\Domain\Model\ThumbId;

class InMemoryThumbRepository implements PhotoThumbRepository
{
    /** @var PhotoThumb[] */
    private $thumbs;

    public function __construct()
    {
        $this->thumbs = [
            new PhotoThumb(
                new ThumbId(),
                new PhotoId(),
                new HttpUrl('http://test'),
                new PhotoThumbSize(1,1),
                new PhotoThumbMode(PhotoThumbMode::THUMBNAIL_OUTBOUND)
            )
        ];
    }

    /**
     * @param PhotoId $photoId
     * @param PhotoThumbSize $thumbSize
     * @param PhotoThumbMode $thumbMode
     * @return PhotoThumb | null
     */
    public function findOneBy(PhotoId $photoId, PhotoThumbSize $thumbSize, PhotoThumbMode $thumbMode)
    {
        foreach ($this->thumbs as $thumb) {
            if ($photoId->id() === $thumb->id() &&
                $thumbSize->height() === $thumb->height() &&
                $thumbSize->width() === $thumb->width() &&
                $thumbMode->value() == $thumb->mode()) {

                return $thumb;
            }
        }
    }

    /**
     * @param PhotoThumb $thumb
     * @return void
     */
    public function save(PhotoThumb $thumb)
    {
        $this->thumbs[] = $thumb;
    }

    /**
     * This method should be called once to create the schema of persistence system
     * @return void
     */
    public function initialize()
    {
    }
}
