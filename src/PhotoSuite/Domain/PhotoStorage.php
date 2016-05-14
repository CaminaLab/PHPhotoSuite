<?php

namespace PHPhotoSuit\PhotoSuite\Domain;

interface PhotoStorage
{
    /**
     * @param Photo $photo
     * @return boolean
     */
    public function upload(Photo $photo);

    /**
     * @param Photo $photo
     * @return boolean
     */
    public function remove(Photo $photo);

    /**
     * @param ResourceId $resourceId
     * @return HttpUrl
     */
    public function getBaseHttpUrlBy(ResourceId $resourceId);

    /**
     * @param string $file
     * @return PhotoFormat
     */
    public function getPhotoFormat($file);
}