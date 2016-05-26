<?php

namespace PHPhotoSuit\PhotoSuite\Application\Service;

use PHPhotoSuit\PhotoSuite\Domain\Exception\PhotoNotFoundException;
use PHPhotoSuit\PhotoSuite\Domain\Lang;
use PHPhotoSuit\PhotoSuite\Domain\Model\Photo;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoAlt;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoAltCollection;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoFile;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoId;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoName;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoRepository;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoStorage;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoThumb;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoThumbRepository;
use PHPhotoSuit\PhotoSuite\Domain\ResourceId;

class PersistHandler
{
    /** @var PhotoRepository */
    private $repository;
    /** @var PhotoStorage */
    private $storage;
    /** @var PhotoThumbRepository */
    private $thumbRepository;

    /**
     * @param PhotoRepository $repository
     * @param PhotoStorage $storage
     * @param PhotoThumbRepository $thumbRepository
     */
    public function __construct(
        PhotoRepository $repository,
        PhotoStorage $storage,
        PhotoThumbRepository $thumbRepository
    ) {
        $this->repository = $repository;
        $this->storage = $storage;
        $this->thumbRepository = $thumbRepository;
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
     * @param SavePhotoRequest $request
     * @return void
     */
    public function saveUnique(SavePhotoRequest $request)
    {
        try {
            $photo = $this->repository->findOneBy(new ResourceId($request->resourceId()));
            $this->delete($photo->id());
        } catch (PhotoNotFoundException $e) {}
        $this->save($request);
    }

    /**
     * @param string $id
     * @return void
     */
    public function delete($id)
    {
        $photoId = new PhotoId($id);
        $photo = $this->repository->findById($photoId);
        $thumbCollection = $this->thumbRepository->findCollectionBy($photoId);
        /** @var PhotoThumb $thumb */
        foreach ($thumbCollection as $thumb) {
            if ($this->storage->removeThumb($thumb)) {
                $this->thumbRepository->delete($thumb);
            }
        }
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
        $photoId = $this->repository->ensureUniquePhotoId();
        return new Photo(
            $photoId,
            $resourceId,
            $photoName,
            $this->storage->getPhotoHttpUrlBy($photoId, $resourceId, $photoName, $photoFile),
            new PhotoAltCollection([new PhotoAlt($photoId, $request->alt(), new Lang($request->lang()))]),
            $photoFile
        );
    }
}
