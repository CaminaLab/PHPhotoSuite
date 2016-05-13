<?php

namespace PHPhotoSuit\PhotoSuite\Domain;

use PHPhotoSuit\PhotoSuite\Domain\Exception\CollectionNotFoundException;
use PHPhotoSuit\PhotoSuite\Domain\Exception\PhotoNotFoundException;

interface PhotoRepository
{
    /**
     * @param ResourceId $resourceId
     * @return Photo
     * @throws PhotoNotFoundException
     */
    public function findOneBy(ResourceId $resourceId);

    /**
     * @param ResourceId $resourceId
     * @return PhotoCollection;
     * @throws CollectionNotFoundException
     */
    public function findCollectionBy(ResourceId $resourceId);
}
