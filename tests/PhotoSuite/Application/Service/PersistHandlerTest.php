<?php

namespace PHPhotoSuit\Tests\PhotoSuite\Application\Service;

use PHPhotoSuit\PhotoSuite\Application\Service\PersistHandler;
use PHPhotoSuit\PhotoSuite\Application\Service\SavePhotoRequest;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoRepository;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoStorage;
use PHPhotoSuit\PhotoSuite\Domain\ResourceId;
use PHPhotoSuit\PhotoSuite\Infrastructure\Storage\LocalStorageConfig;
use PHPhotoSuit\PhotoSuite\Infrastructure\Storage\PhotoLocalStorage;

class PersistHandlerTest extends \PHPUnit_Framework_TestCase
{
    /** @var PersistHandler */
    private $persistHander;
    /** @var PhotoRepository */
    private $repository;
    /** @var PhotoStorage */
    private $storage;
    /** @var LocalStorageConfig */
    private $localStorageConfig;
    /** @var string */
    private $httpUrlBasePath = 'http://test';
    /** @var string */
    private $storagePath = __DIR__ . '/../../Infrastructure/Storage/storage_test_folder';

    public function setUp()
    {
        $this->repository = new InMemoryPhotoRepository();
        $this->localStorageConfig = new LocalStorageConfig($this->storagePath, $this->httpUrlBasePath);
        $this->storage = new PhotoLocalStorage($this->localStorageConfig);
        $this->persistHander = new PersistHandler($this->repository, $this->storage);
    }

    /**
     * @test
     */
    public function saveAndDeletePhotoWorks()
    {
        $resourceId = 'newphoto';
        $request = new SavePhotoRequest($resourceId, 'test', __DIR__ . '/../../Domain/pixel.png');
        $this->persistHander->save($request);
        $photo = $this->repository->findOneBy(new ResourceId($resourceId));
        $this->assertFileExists($photo->photoFile()->filePath());
        $this->persistHander->delete($photo->id());
        $this->assertFileNotExists($photo->photoFile()->filePath());
    }
}
