<?php

namespace PHPhotoSuit\Tests\PhotoSuite\Infrastructure\Storage;

use PHPhotoSuit\PhotoSuite\Domain\Photo;
use PHPhotoSuit\PhotoSuite\Domain\PhotoFile;
use PHPhotoSuit\PhotoSuite\Domain\PhotoId;
use PHPhotoSuit\PhotoSuite\Domain\PhotoName;
use PHPhotoSuit\PhotoSuite\Domain\ResourceId;
use PHPhotoSuit\PhotoSuite\Infrastructure\Storage\LocalStorageConfig;
use PHPhotoSuit\PhotoSuite\Infrastructure\Storage\PhotoLocalStorage;

class PhotoLocalStorageTest extends \PHPUnit_Framework_TestCase
{
    /** @var string */
    private $storagePath = __DIR__ . '/storage_test_folder';
    /** @var string */
    private $urlBase = 'http://test';
    /** @var LocalStorageConfig */
    private $config;
    /** @var PhotoLocalStorage */
    private $storage;

    public function setUp()
    {
        $this->config = new LocalStorageConfig($this->storagePath, $this->urlBase);
        $this->storage = new PhotoLocalStorage($this->config);
    }

    public function getBaseHttpUrlByResourceIdReturnsExpected()
    {
        $resourceId = new ResourceId('test');
        $name = new PhotoName('test');
        $file = new PhotoFile(__DIR__ . '/photo_to_upload.png');
        $this->assertEquals(
            implode('/', [$this->urlBase, $this->getMd5Path($resourceId->id()), $name->slug()]) . '.' . $file->format(),
            $this->storage->getPhotoHttpUrlBy($resourceId, $name, $file)->value()
        );
    }

    /**
     * @test
     */
    public function uploadAndDeleteOnePhotoWorks()
    {
        $photoId = new PhotoId();
        $resourceId = new ResourceId('test');
        $photoName = new PhotoName('test');
        $photoFile = new PhotoFile(__DIR__ . '/photo_to_upload.png');
        $this->storage->upload(new Photo(
            $photoId,
            $resourceId,
            $photoName,
            $this->storage->getPhotoHttpUrlBy($resourceId, $photoName, $photoFile),
            $photoFile
        ));
        $uploadedPhoto = $this->config->storagePath() . '/' .
                        $this->getMd5Path($resourceId->id()). '/' .
                        $photoName->slug() . '.' .
                        $photoFile->format();

        $this->assertTrue(file_exists($uploadedPhoto));

        $this->assertTrue(
            $this->storage->remove(
                new Photo(
                    $photoId,
                    $resourceId,
                    $photoName,
                    $this->storage->getPhotoHttpUrlBy($resourceId, $photoName, $photoFile),
                    new PhotoFile($uploadedPhoto)
                )
            )
        );
    }

    private function getMd5Path($value)
    {
        return substr(md5($value), 0, 2) . '/' .
        substr(md5($value), 2, 2) . '/' .
        substr(md5($value), 4, 2) . '/' .
        substr(md5($value), 6, 2);
    }
}