<?php

namespace PHPhotoSuit\PhotoSuite\Application\Services;

use PHPhotoSuit\PhotoSuite\Domain\PhotoPresenter;
use PHPhotoSuit\PhotoSuite\Domain\PhotoRepository;
use PHPhotoSuit\PhotoSuite\Domain\ResourceId;

class Finder
{
    /** @var PhotoRepository */
    private $repository;
    /** @var PhotoPresenter */
    private $presenter;

    /**
     * @param PhotoRepository $repository
     * @param PhotoPresenter $presenter
     */
    public function __construct(PhotoRepository $repository, PhotoPresenter $presenter)
    {
        $this->repository = $repository;
        $this->presenter = $presenter;
    }

    /**
     * @param ResourceId $resourceId
     * @return mixed
     */
    public function findPhotoOf(ResourceId $resourceId) {
        return $this->presenter->write($this->repository->findOneBy($resourceId));
    }

    /**
     * @param ResourceId $resourceId
     * @return mixed
     */
    public function findPhotoCollectionOf(ResourceId $resourceId) {
        return $this->presenter->writeCollection($this->repository->findCollectionBy($resourceId));
    }
}
