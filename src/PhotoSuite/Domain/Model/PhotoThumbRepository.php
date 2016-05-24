<?php

namespace PHPhotoSuit\PhotoSuite\Domain\Model;

interface PhotoThumbRepository
{
    /**
     * @param PhotoId $photoId
     * @param PhotoThumbSize $thumbSize
     * @param PhotoThumbMode $thumbMode
     * @return PhotoThumb | null
     */
    public function findOneBy(PhotoId $photoId, PhotoThumbSize $thumbSize, PhotoThumbMode $thumbMode);

    /**
     * @param PhotoThumb $thumb
     * @return void
     */
    public function save(PhotoThumb $thumb);
}
