<?php

namespace PHPhotoSuit\PhotoSuite\Domain\Model;

interface PhotoThumbPresenter
{
    /**
     * @param Photo $photo
     * @param PhotoThumbCollection $thumbCollection
     * @return mixed
     */
    public function write(Photo $photo, PhotoThumbCollection $thumbCollection);
}
