<?php

namespace PHPhotoSuit\Tests\PhotoSuite\Application\Service;

use PHPhotoSuit\PhotoSuite\Domain\Exception\CollectionNotFoundException;
use PHPhotoSuit\PhotoSuite\Domain\Exception\PhotoNotFoundException;
use PHPhotoSuit\PhotoSuite\Domain\HttpUrl;
use PHPhotoSuit\PhotoSuite\Domain\Photo;
use PHPhotoSuit\PhotoSuite\Domain\PhotoCollection;
use PHPhotoSuit\PhotoSuite\Domain\PhotoFile;
use PHPhotoSuit\PhotoSuite\Domain\PhotoFormat;
use PHPhotoSuit\PhotoSuite\Domain\PhotoId;
use PHPhotoSuit\PhotoSuite\Domain\PhotoName;
use PHPhotoSuit\PhotoSuite\Domain\PhotoRepository;
use PHPhotoSuit\PhotoSuite\Domain\ResourceId;

class InMemoryPhotoRepository implements PhotoRepository
{
    /** @var Photo[] */
    private $photos;

    public function __construct()
    {
        $this->photos = [
            $photo = new Photo(
                new PhotoId(),
                new ResourceId(1),
                new PhotoName('testing'),
                new HttpUrl('http://works')
            ),
            $photo = new Photo(
                new PhotoId(),
                new ResourceId(1),
                new PhotoName('testing'),
                new HttpUrl('http://works')
            ),
            $photo = new Photo(
                new PhotoId(),
                new ResourceId(2),
                new PhotoName('testing'),
                new HttpUrl('http://works')
            )
        ];
    }

    /**
     * @param ResourceId $resourceId
     * @return Photo
     * @throws PhotoNotFoundException
     */
    public function findOneBy(ResourceId $resourceId)
    {
        foreach ($this->photos as $photo) {
            if ($photo->resourceId() === $resourceId->id()) {
                return $photo;
            }
        }
        throw new PhotoNotFoundException(sprintf('Photo of resource %s not found', $resourceId->id()));
    }

    /**
     * @param PhotoId $photoId
     * @return mixed|Photo
     * @throws PhotoNotFoundException
     */
    public function findById(PhotoId $photoId)
    {
        foreach ($this->photos as $photo) {
            if ($photo->id() === $photoId->id()) {
                return $photo;
            }
        }
        throw new PhotoNotFoundException(sprintf('Photo of id %s not found', $photoId->id()));
    }


    /**
     * @param ResourceId $resourceId
     * @return PhotoCollection;
     * @throws CollectionNotFoundException
     */
    public function findCollectionBy(ResourceId $resourceId)
    {
        $photos = [];
        foreach ($this->photos as $photo) {
            if ($photo->resourceId() === $resourceId->id()) {
                $photos[] = $photo;
            }
        }
        if (empty($photos)) {
            throw new CollectionNotFoundException(sprintf('Collection of resource %s not found', $resourceId->id()));
        }
        return new PhotoCollection($photos);
    }

    /**
     * @param Photo $photo
     * @return void
     */
    public function save(Photo $photo)
    {
        $this->photos[] = $photo;
    }

    /**
     * @param Photo $photo
     * @return void
     */
    public function delete(Photo $photo)
    {
        $key = array_search($photo, $this->photos);
        if ($key !== false) {
            unset($this->photos[$key]);
        }
    }
}
