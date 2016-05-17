<?php

namespace PHPhotoSuit\PhotoSuite\Application\Service;

use PHPhotoSuit\PhotoSuite\Domain\Photo;
use PHPhotoSuit\PhotoSuite\Domain\PhotoFile;
use PHPhotoSuit\PhotoSuite\Domain\PhotoName;
use PHPhotoSuit\PhotoSuite\Domain\PhotoRepository;
use PHPhotoSuit\PhotoSuite\Domain\PhotoStorage;
use PHPhotoSuit\PhotoSuite\Domain\ResourceId;

class PersistHandler
{
    /** @var PhotoRepository */
    private $repository;
    /** @var PhotoStorage */
    private $storage;

    /**
     * @param PhotoRepository $repository
     * @param PhotoStorage $storage
     */
    public function __construct(PhotoRepository $repository, PhotoStorage $storage)
    {
        $this->repository = $repository;
        $this->storage = $storage;
    }

    /**
     * @param SavePhotoRequest $request
     * @return void
     */
    public function save(SavePhotoRequest $request)
    {
        $photo = $this->createPhotoBy($request);
        if ($this->storage->upload($photo)) {
            $this->repository->save($photo);
        }
    }

    /**
     * @param Photo $photo
     * @return void
     */
    public function delete(Photo $photo)
    {
        if ($this->storage->remove($photo)) {
            $this->repository->delete($photo);
        }
    }

    /**
     * @param SavePhotoRequest $request
     * @return Photo
     */
    private function createPhotoBy(SavePhotoRequest $request)
    {
        $resourceId = new ResourceId($request->resourceId());
        return new Photo(
            $resourceId,
            new PhotoName($request->name()),
            PhotoFile::getInstanceBy($request->file()),
            $this->storage->getBaseHttpUrlBy($resourceId)
        );
    }
}
