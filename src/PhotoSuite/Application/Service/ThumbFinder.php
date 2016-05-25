<?php

namespace PHPhotoSuit\PhotoSuite\Application\Service;

use PHPhotoSuit\PhotoSuite\Domain\Model\CollectionOfThumbCollection;
use PHPhotoSuit\PhotoSuite\Domain\Model\Photo;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoId;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoName;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoRepository;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoStorage;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoThumb;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoThumbCollection;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoThumbGenerator;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoThumbPresenter;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoThumbRepository;
use PHPhotoSuit\PhotoSuite\Domain\ResourceId;

class ThumbFinder
{
    /** @var PhotoRepository */
    private $photoRepository;
    /** @var PhotoThumbRepository */
    private $thumbRepository;
    /** @var PhotoThumbGenerator */
    private $thumbGenerator;
    /** @var PhotoStorage */
    private $photoStorage;
    /** @var PhotoThumbPresenter */
    private $thumbPresenter;

    /**
     * ThumbFinder constructor.
     * @param PhotoRepository $photoRepository
     * @param PhotoThumbRepository $thumbRepository
     * @param PhotoThumbGenerator $thumbGenerator
     * @param PhotoStorage $photoStorage
     * @param PhotoThumbPresenter $thumbPresenter
     */
    public function __construct(
        PhotoRepository $photoRepository,
        PhotoThumbRepository $thumbRepository,
        PhotoThumbGenerator $thumbGenerator,
        PhotoStorage $photoStorage,
        PhotoThumbPresenter $thumbPresenter
    ) {
        $this->photoRepository = $photoRepository;
        $this->thumbRepository = $thumbRepository;
        $this->thumbGenerator = $thumbGenerator;
        $this->photoStorage = $photoStorage;
        $this->thumbPresenter = $thumbPresenter;
    }

    /**
     * @param ThumbFinderRequest $request
     * @return mixed
     */
    public function findPhotoThumbsOf(ThumbFinderRequest $request)
    {
        $photo = $this->photoRepository->findOneBy($request->resourceId());
        $thumbCollection = $this->getThumbsCollectionOfPhoto($request->thumbRequestCollection(), $photo);
        return $this->thumbPresenter->write($photo, $thumbCollection);
    }

    /**
     * @param ThumbFinderRequest $request
     * @return mixed
     */
    public function findPhotoCollectionWithItsThumbsOf(ThumbFinderRequest $request)
    {
        $photoCollection = $this->photoRepository->findCollectionBy($request->resourceId());
        $collectionOfThumbCollection = new CollectionOfThumbCollection();
        foreach ($photoCollection as $photo) {
            $collectionOfThumbCollection[] = $this->getThumbsCollectionOfPhoto($request->thumbRequestCollection(), $photo);
        }
        return $this->thumbPresenter->writeCollection($photoCollection, $collectionOfThumbCollection);
    }

    /**
     * @param ThumbRequestCollection $thumbRequestCollection
     * @param Photo $photo
     * @return array|PhotoThumbCollection
     */
    public function getThumbsCollectionOfPhoto(ThumbRequestCollection $thumbRequestCollection, Photo $photo)
    {
        $thumbCollection = new PhotoThumbCollection();
        /** @var ThumbRequest $thumbRequest */
        foreach ($thumbRequestCollection as $thumbRequest) {
            $thumb = $this->thumbRepository->findOneBy(
                new PhotoId($photo->id()),
                $thumbRequest->thumbSize()
            );
            if (is_null($thumb)) {
                $thumb = $this->createThumbFromOriginal($photo, $thumbRequest);
            }
            $thumbCollection[] = $thumb;
        }
        return $thumbCollection;
    }

    /**
     * @param Photo $photo
     * @param ThumbRequest $thumbRequest
     * @return PhotoThumb
     */
    private function createThumbFromOriginal(Photo $photo, ThumbRequest $thumbRequest)
    {
        $thumb = $this->thumbGenerator->generate(
            $this->thumbRepository->ensureUniqueThumbId(),
            $photo,
            $thumbRequest->thumbSize(),
            $this->photoStorage->getPhotoThumbHttpUrlBy(
                new PhotoId($photo->id()),
                new ResourceId($photo->resourceId()),
                new PhotoName($photo->name()),
                $thumbRequest->thumbSize(),
                $this->thumbGenerator->conversionFormat()
            )
        );
        $photoFile = $this->photoStorage->uploadThumb($thumb, $photo);
        $thumb->updatePhotoThumbFile($photoFile);
        $this->thumbRepository->save($thumb);

        return $thumb;
    }
}
