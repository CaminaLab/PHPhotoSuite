<?php

namespace PHPhotoSuit\PhotoSuite\Domain;

interface PhotoPresenter
{
    /**
     * @param Photo $photo
     * @return mixed
     */
    public function write(Photo $photo);

    /**
     * @param PhotoCollection $collection
     * @return mixed
     */
    public function writeCollection(PhotoCollection $collection);
}
