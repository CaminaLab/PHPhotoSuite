<?php

namespace PHPhotoSuit\PhotoSuite\Infrastructure\Storage;

use PHPhotoSuit\PhotoSuite\Domain\HttpUrl;
use PHPhotoSuit\PhotoSuite\Domain\Photo;
use PHPhotoSuit\PhotoSuite\Domain\PhotoFile;
use PHPhotoSuit\PhotoSuite\Domain\PhotoName;
use PHPhotoSuit\PhotoSuite\Domain\PhotoStorage;
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
        $destinyPath = $this->localStorageConfig->storagePath() . '/' . $this->getMd5Path($photo->resourceId());
        $this->createPathIfNotExists($destinyPath);
        $newPhotoFilePath = $destinyPath . '/' . $photo->slug() . '.' . $photo->photoFile()->format();
        copy(
            $photo->photoFile()->filePath(),
            $newPhotoFilePath
        );
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
     * @param ResourceId $resourceId
     * @param PhotoName $photoName
     * @param PhotoFile $photoFile
     * @return HttpUrl
     */
    public function getPhotoHttpUrlBy(ResourceId $resourceId, PhotoName $photoName, PhotoFile $photoFile)
    {
        $urlBase = $this->localStorageConfig->urlBase();
        $urlBase = $urlBase[strlen($urlBase)-1] === '/' ? substr($urlBase, 0, -1) : $urlBase;
        return new HttpUrl(
            implode(
                '/', 
                [$urlBase, $this->getMd5Path($resourceId->id()), $photoName->slug()]
            ) . '.' . $photoFile->format()
        );
    }


    /**
     * @param $value
     * @return string
     */
    private function getMd5Path($value)
    {
        return substr(md5($value), 0, 2) . '/' .
        substr(md5($value), 2, 2) . '/' .
        substr(md5($value), 4, 2) . '/' .
        substr(md5($value), 6, 2);
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
}
