<?php

namespace PHPhotoSuit\PhotoSuite\Domain\Model;

use PHPhotoSuit\PhotoSuite\Domain\Exception\CollectionNotFoundException;
use PHPhotoSuit\PhotoSuite\Domain\Exception\PhotoNotFoundException;
use PHPhotoSuit\PhotoSuite\Domain\ResourceId;

interface PhotoRepository
{
    /**
     * This method should be called once to create the schema of persistence system
     * @return void
     */
    public function initialize();
    
    /**
     * @param ResourceId $resourceId
     * @return Photo
     * @throws PhotoNotFoundException
     */
    public function findOneBy(ResourceId $resourceId);

    /**
     * @param PhotoId $photoId
     * @return mixed
     * @throws PhotoNotFoundException
     */
    public function findById(PhotoId $photoId);
    
    /**
     * @param ResourceId $resourceId
     * @return PhotoCollection;
     * @throws CollectionNotFoundException
     */
    public function findCollectionBy(ResourceId $resourceId);

    /**
     * @param Photo $photo
     * @return void
     */
    public function save(Photo $photo);

    /**
     * @param Photo $photo
     * @return void
     */
    public function delete(Photo $photo);
}
