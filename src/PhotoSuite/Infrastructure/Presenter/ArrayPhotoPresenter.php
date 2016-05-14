<?php

namespace PHPhotoSuit\PhotoSuite\Infrastructure\Presenter;

use PHPhotoSuit\PhotoSuite\Domain\Photo;
use PHPhotoSuit\PhotoSuite\Domain\PhotoCollection;
use PHPhotoSuit\PhotoSuite\Domain\PhotoPresenter;

class ArrayPhotoPresenter implements PhotoPresenter
{

    /**
     * @param Photo $photo
     * @return array
     */
    public function write(Photo $photo)
    {
        return [
            'resourceId' => $photo->resourceId(),
            'name' => $photo->name(),
            'url' => $photo->getPhotoHttpUrl()
        ];
    }

    /**
     * @param PhotoCollection $collection
     * @return array
     */
    public function writeCollection(PhotoCollection $collection)
    {
        return array_map([ArrayPhotoPresenter::class, 'write'], $collection->toArray());
    }
}
