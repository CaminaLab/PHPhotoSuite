<?php

namespace PHPhotoSuit\PhotoSuite\Application\Service;

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


    public function findThumbsOf(ThumbFinderRequest $request)
    {
        $photo = $this->photoRepository->findOneBy($request->resourceId());
        $thumbCollection = new PhotoThumbCollection();
        /** @var ThumbRequest $thumbRequest */
        foreach ($request->thumbRequestCollection() as $thumbRequest) {
            $thumb = $this->thumbRepository->findOneBy(
                new PhotoId($photo->id()),
                $thumbRequest->thumbSize(),
                $thumbRequest->thumbMode()
            );
            if (is_null($thumb)) {
                $thumb = $this->createThumbFromOriginal($photo, $thumbRequest);
            }
            $thumbCollection[] = $thumb;
        }

        return $this->thumbPresenter->write($photo, $thumbCollection);
    }

    /**
     * @param Photo $photo
     * @param ThumbRequest $thumbRequest
     * @return PhotoThumb
     */
    private function createThumbFromOriginal(Photo $photo, ThumbRequest $thumbRequest)
    {
        $thumb = $this->thumbGenerator->generate(
            $photo,
            $thumbRequest->thumbSize(),
            $thumbRequest->thumbMode(),
            $this->photoStorage->getPhotoThumbHttpUrlBy(
                new PhotoId($photo->id()),
                new ResourceId($photo->resourceId()),
                new PhotoName($photo->name()),
                $thumbRequest->thumbSize(),
                $thumbRequest->thumbMode(),
                $this->thumbGenerator->conversionFormat()
            )
        );
        $photoFile = $this->photoStorage->uploadThumb($thumb);
        $thumb->updatePhotoThumbFile($photoFile);
        $this->thumbRepository->save($thumb);
        return $thumb;
    }
}
