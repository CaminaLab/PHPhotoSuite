<?php

namespace PHPhotoSuit\PhotoSuite\Application\Service;

use PHPhotoSuit\PhotoSuite\Domain\Model\Photo;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoFile;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoId;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoName;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoRepository;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoStorage;
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
        $newPathPhotoFile = $this->storage->upload($photo);
        $photo->updatePhotoFile($newPathPhotoFile);
        $this->repository->save($photo);
        
    }

    /**
     * @param string $id
     * @return void
     */
    public function delete($id)
    {
        $photo = $this->repository->findById(new PhotoId($id));
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
        $photoName = new PhotoName($request->name());
        $photoFile = new PhotoFile($request->file());
        return new Photo(
            $this->repository->ensureUniquePhotoId(),
            $resourceId,
            $photoName,
            $this->storage->getPhotoHttpUrlBy($resourceId, $photoName, $photoFile),
            $photoFile
        );
    }
}
