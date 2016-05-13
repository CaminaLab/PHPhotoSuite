<?php

namespace PHPhotoSuit\PhotoSuite\Infrastructure\Presenter;

use PHPhotoSuit\PhotoSuite\Domain\Photo;
use PHPhotoSuit\PhotoSuite\Domain\PhotoCollection;
use PHPhotoSuit\PhotoSuite\Domain\PhotoPresenter;

class ArrayPhotoPresenter implements PhotoPresenter
{

    /**
     * @param Photo $photo
     * @return mixed
     */
    public function write(Photo $photo)
    {
        
    }

    /**
     * @param PhotoCollection $collection
     * @return mixed
     */
    public function writeCollection(PhotoCollection $collection)
    {
        // TODO: Implement writeCollection() method.
    }
}
