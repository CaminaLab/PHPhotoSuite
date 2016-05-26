<?php

namespace PHPhotoSuit\Tests\PhotoSuite\Application\Service;

use PHPhotoSuit\PhotoSuite\Application\Service\PersistHandler;
use PHPhotoSuit\PhotoSuite\Application\Service\SavePhotoRequest;
use PHPhotoSuit\PhotoSuite\Domain\Exception\PhotoNotFoundException;
use PHPhotoSuit\PhotoSuite\Domain\HttpUrl;
use PHPhotoSuit\PhotoSuite\Domain\Model\Photo;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoAltCollection;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoId;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoName;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoRepository;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoStorage;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoThumb;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoThumbRepository;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoThumbSize;
use PHPhotoSuit\PhotoSuite\Domain\Model\ThumbId;
use PHPhotoSuit\PhotoSuite\Domain\ResourceId;
use PHPhotoSuit\PhotoSuite\Infrastructure\Storage\LocalStorageConfig;
use PHPhotoSuit\PhotoSuite\Infrastructure\Storage\PhotoLocalStorage;

class PersistHandlerTest extends \PHPUnit_Framework_TestCase
{
    /** @var PersistHandler */
    private $persistHander;
    /** @var PhotoRepository */
    private $repository;
    /** @var PhotoThumbRepository */
    private $thumbRepository;
    /** @var PhotoStorage */
    private $storage;
    /** @var LocalStorageConfig */
    private $localStorageConfig;
    /** @var string */
    private $httpUrlBasePath = 'http://test';
    /** @var string */
    private $storagePath = __DIR__ . '/../../Infrastructure/Storage/storage_test_folder';
    /** @var  SavePhotoRequest */
    private $request;
    /** @var string */
    private $resourceId = 'newphoto';

    public function setUp()
    {
        $this->repository = new InMemoryPhotoRepository();
        $this->thumbRepository = new InMemoryThumbRepository();
        $this->localStorageConfig = new LocalStorageConfig($this->storagePath, $this->httpUrlBasePath);
        $this->storage = new PhotoLocalStorage($this->localStorageConfig);
        $this->persistHander = new PersistHandler($this->repository, $this->storage, $this->thumbRepository);
        $this->request = new SavePhotoRequest($this->resourceId, 'test', __DIR__ . '/pixel.png', 'alt', 'ES');
    }

    /**
     * @test
     */
    public function saveAndDeletePhotoWorks()
    {
        $this->persistHander->save($this->request);
        $photo = $this->repository->findOneBy(new ResourceId($this->resourceId));
        $this->assertFileExists($photo->photoFile()->filePath());
        $this->persistHander->delete($photo->id());
        $this->assertFileNotExists($photo->photoFile()->filePath());
    }

    /**
     * @test
     */
    public function saveUnique()
    {
        $this->persistHander->saveUnique($this->request);
        $photo = $this->repository->findOneBy(new ResourceId($this->resourceId));
        $this->assertFileExists($photo->photoFile()->filePath());
        $this->persistHander->saveUnique($this->request);
        $photo = $this->repository->findOneBy(new ResourceId($this->resourceId));
        $this->assertFileExists($photo->photoFile()->filePath());
        $this->persistHander->delete($photo->id());
    }
}
