<?php

namespace PHPhotoSuit\PhotoSuite\Infrastructure\Storage;

use PHPhotoSuit\PhotoSuite\Domain\HttpUrl;
use PHPhotoSuit\PhotoSuite\Domain\Model\Photo;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoFile;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoFormat;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoId;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoName;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoStorage;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoThumb;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoThumbMode;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoThumbSize;
use PHPhotoSuit\PhotoSuite\Domain\ResourceId;

class PhotoLocalStorage implements PhotoStorage
{

    /** @var LocalStorageConfig */
    private $localStorageConfig;

    /**
     * @param LocalStorageConfig $localStorageConfig
     */
    public function __construct(LocalStorageConfig $localStorageConfig)
    {
        $this->localStorageConfig = $localStorageConfig;
    }

    /**
     * @param Photo $photo
     * @return PhotoFile | null
     */
    public function upload(Photo $photo)
    {
        $destinyPath = $this->localStorageConfig->storagePath() . '/' .
                        $this->getMd5Path($photo->resourceId()) . '/' .
                        $photo->id();
        $this->createPathIfNotExists($destinyPath);
        $newPhotoFilePath = $destinyPath . '/' . $photo->slug() . '.' . $photo->photoFile()->format();
        copy($photo->photoFile()->filePath(), $newPhotoFilePath);

        return new PhotoFile($newPhotoFilePath);
    }

    /**
     * @param Photo $photo
     * @return boolean
     */
    public function remove(Photo $photo)
    {
        return unlink($photo->photoFile()->filePath());
    }

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
    ) {
        $urlBase = $this->localStorageConfig->urlBase();
        $urlBase = $urlBase[strlen($urlBase)-1] === '/' ? substr($urlBase, 0, -1) : $urlBase;
        return new HttpUrl(
            implode(
                '/', 
                [$urlBase, $this->getMd5Path($resourceId->id()), $photoId->id(), $photoName->slug()]
            ) . '.' . $photoFile->format()
        );
    }


    /**
     * @param $value
     * @return string
     */
    private function getMd5Path($value)
    {
        $md5 = md5($value);
        return substr($md5, 0, 2) . '/' . substr($md5, 2, 2) . '/' . substr($md5, 4, 2) . '/' . substr($md5, 6, 2);
    }

    /**
     * @param $destinyPath
     * @return void
     */
    private function createPathIfNotExists($destinyPath)
    {
        if (!file_exists($destinyPath)) {
            mkdir($destinyPath, 0775, true);
        }
    }

    /**
     * @param PhotoThumb $thumb
     * @param Photo $photo
     * @return null|PhotoFile
     */
    public function uploadThumb(PhotoThumb $thumb, Photo $photo)
    {
        $destinyPath = $this->localStorageConfig->storagePath() . '/' .
                        $this->getMd5Path($photo->resourceId()) . '/' .
                        $photo->id();
        $this->createPathIfNotExists($destinyPath);
        $newPhotoFilePath = sprintf(
            '%s/%s_%dx%d.%s',
            $destinyPath,
            $photo->slug(),
            $thumb->width(),
            $thumb->height(),
            $thumb->photoThumbFile()->format()
        );
        copy($photo->photoFile()->filePath(), $newPhotoFilePath);

        return new PhotoFile($newPhotoFilePath);
    }

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
    ) {
        $urlBase = $this->localStorageConfig->urlBase();
        $urlBase = $urlBase[strlen($urlBase)-1] === '/' ? substr($urlBase, 0, -1) : $urlBase;
        return new HttpUrl(
            implode(
                '/',
                [
                    $urlBase,
                    $this->getMd5Path($resourceId->id()),
                    $photoId->id(),
                    $photoName->slug(),
                    '_' . $photoThumbSize->height() . 'x' . $photoThumbSize->width()
                ]
            ) . '.' . $photoFormat->value()
        );
    }
}
