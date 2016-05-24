<?php

namespace PHPhotoSuit\PhotoSuite\Domain\Model;

use PHPhotoSuit\PhotoSuite\Domain\HttpUrl;
use PHPhotoSuit\PhotoSuite\Domain\ResourceId;

interface PhotoStorage
{
    /**
     * @param Photo $photo
     * @return PhotoFile | null
     */
    public function upload(Photo $photo);

    /**
     * @param Photo $photo
     * @return boolean
     */
    public function remove(Photo $photo);

    /**
     * @param PhotoId $photoId
     * @param ResourceId $resourceId
     * @param PhotoName $photoName
     * @param PhotoFile $photoFile
     * @return HttpUrl
     */
    public function getPhotoHttpUrlBy(
        PhotoId $photoId,
        ResourceId $resourceId,
        PhotoName $photoName,
        PhotoFile $photoFile
    );

    /**
     * @param PhotoThumb $thumb
     * @return PhotoFile | null
     */
    public function uploadThumb(PhotoThumb $thumb);

    /**
     * @param PhotoId $photoId
     * @param ResourceId $resourceId
     * @param PhotoName $photoName
     * @param PhotoThumbSize $photoThumbSize
     * @param PhotoFormat $photoFormat
     * @return HttpUrl
     */
    public function getPhotoThumbHttpUrlBy(
        PhotoId $photoId,
        ResourceId $resourceId,
        PhotoName $photoName,
        PhotoThumbSize $photoThumbSize,
        PhotoFormat $photoFormat
    );
}