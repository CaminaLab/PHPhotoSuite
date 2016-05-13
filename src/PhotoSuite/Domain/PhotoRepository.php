<?php

namespace PHPhotoSuit\PhotoSuite\Domain;

interface PhotoRepository
{
    /**
     * @param ResourceId $resourceId
     * @return Photo
     */
    public function findOneBy(ResourceId $resourceId);

    /**
     * @param ResourceId $resourceId
     * @return PhotoCollection;
     */
    public function findCollectionBy(ResourceId $resourceId);
}
