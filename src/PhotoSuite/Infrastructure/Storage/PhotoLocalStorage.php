<?php

namespace PHPhotoSuit\PhotoSuite\Infrastructure\Storage;

use PHPhotoSuit\PhotoSuite\Domain\HttpUrl;
use PHPhotoSuit\PhotoSuite\Domain\Photo;
use PHPhotoSuit\PhotoSuite\Domain\PhotoStorage;
use PHPhotoSuit\PhotoSuite\Domain\ResourceId;

class PhotoLocalStorage implements PhotoStorage
{

    /**
     * @param Photo $photo
     * @return boolean
     */
    public function upload(Photo $photo)
    {
        // TODO: Implement upload() method.
    }

    /**
     * @param Photo $photo
     * @return boolean
     */
    public function remove(Photo $photo)
    {
        // TODO: Implement remove() method.
    }

    /**
     * @param ResourceId $resourceId
     * @return HttpUrl
     */
    public function getBaseHttpUrlBy(ResourceId $resourceId)
    {
        // TODO: Implement getBaseHttpUrlBy() method.
    }
}
