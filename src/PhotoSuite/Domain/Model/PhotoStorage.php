<?php

namespace PHPhotoSuit\PhotoSuite\Domain\Model;

use PHPhotoSuit\PhotoSuite\Domain\HttpUrl;
use PHPhotoSuit\PhotoSuite\Domain\ResourceId;

interface PhotoStorage
{
    /**
     * @param Photo $photo
     * @return PhotoFile | null
     */
    public function upload(Photo $photo);

    /**
     * @param Photo $photo
     * @return boolean
     */
    public function remove(Photo $photo);

    /**
     * @param ResourceId $resourceId
     * @param PhotoName $photoName
     * @param PhotoFile $photoFile
     * @return HttpUrl
     */
    public function getPhotoHttpUrlBy(ResourceId $resourceId, PhotoName $photoName, PhotoFile $photoFile);
}