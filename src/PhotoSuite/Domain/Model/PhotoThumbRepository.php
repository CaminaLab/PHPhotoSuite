<?php

namespace PHPhotoSuit\PhotoSuite\Domain\Model;

interface PhotoThumbRepository
{
    /**
     * This method should be called once to create the schema of persistence system
     * @return void
     */
    public function initialize();
    
    /**
     * @param PhotoId $photoId
     * @param PhotoThumbSize $thumbSize
     * @return PhotoThumb | null
     */
    public function findOneBy(PhotoId $photoId, PhotoThumbSize $thumbSize);

    /**
     * @param PhotoThumb $thumb
     * @return void
     */
    public function save(PhotoThumb $thumb);

    /**
     * @param PhotoThumb $thumb
     * @return void
     */
    public function delete(PhotoThumb $thumb);

    /**
     * @param ThumbId $thumbId
     * @return ThumbId
     */
    public function ensureUniqueThumbId(ThumbId $thumbId = null);

    /**
     * @param PhotoId $photoId
     * @return PhotoThumbCollection
     */
    public function findCollectionBy(PhotoId $photoId);
}
