<?php

namespace PHPhotoSuit\PhotoSuite\Infrastructure\Persistence;

use PHPhotoSuit\PhotoSuite\Domain\Exception\CollectionNotFoundException;
use PHPhotoSuit\PhotoSuite\Domain\Exception\PhotoNotFoundException;
use PHPhotoSuit\PhotoSuite\Domain\Photo;
use PHPhotoSuit\PhotoSuite\Domain\PhotoCollection;
use PHPhotoSuit\PhotoSuite\Domain\PhotoId;
use PHPhotoSuit\PhotoSuite\Domain\PhotoRepository;
use PHPhotoSuit\PhotoSuite\Domain\ResourceId;

class SqlitePhotoRepository implements PhotoRepository
{

    /**
     * @param ResourceId $resourceId
     * @return Photo
     * @throws PhotoNotFoundException
     */
    public function findOneBy(ResourceId $resourceId)
    {
        // TODO: Implement findOneBy() method.
    }

    /**
     * @param PhotoId $photoId
     * @return mixed
     * @throws PhotoNotFoundException
     */
    public function findById(PhotoId $photoId)
    {
        // TODO: Implement findById() method.
    }

    /**
     * @param ResourceId $resourceId
     * @return PhotoCollection;
     * @throws CollectionNotFoundException
     */
    public function findCollectionBy(ResourceId $resourceId)
    {
        // TODO: Implement findCollectionBy() method.
    }

    /**
     * @param Photo $photo
     * @return void
     */
    public function save(Photo $photo)
    {
        // TODO: Implement save() method.
    }

    /**
     * @param Photo $photo
     * @return void
     */
    public function delete(Photo $photo)
    {
        // TODO: Implement delete() method.
    }
}
