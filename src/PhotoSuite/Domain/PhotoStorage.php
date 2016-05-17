<?php

namespace PHPhotoSuit\PhotoSuite\Domain;

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