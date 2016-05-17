<?php

namespace PHPhotoSuit\Tests\PhotoSuite\Infrastructure\Storage;

use PHPhotoSuit\PhotoSuite\Domain\Photo;
use PHPhotoSuit\PhotoSuite\Domain\PhotoFile;
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
        $this->assertEquals(
            $this->urlBase . '/' . $this->getMd5Path($resourceId->id()),
            $this->storage->getBaseHttpUrlBy($resourceId)->value()
        );
    }

    /**
     * @test
     */
    public function uploadAndDeleteOnePhotoWorks()
    {
        $resourceId = new ResourceId('test');
        $photoName = new PhotoName('test');
        $photoFile = PhotoFile::getInstanceBy(__DIR__ . '/photo_to_upload.png');
        $this->storage->upload(new Photo(
            $resourceId,
            $photoName,
            $photoFile,
            $this->storage->getBaseHttpUrlBy($resourceId)
        ));
        $uploadedPhoto = $this->config->storagePath() . '/' .
                        $this->getMd5Path($resourceId->id()). '/' .
                        $photoName->slug() . '.' .
                        $photoFile->format();

        $this->assertTrue(file_exists($uploadedPhoto));

        $this->assertTrue(
            $this->storage->remove(
                new Photo(
                    $resourceId,
                    $photoName,
                    PhotoFile::getInstanceBy($uploadedPhoto),
                    $this->storage->getBaseHttpUrlBy($resourceId)
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
