<?php

namespace PHPhotoSuit\Tests\PhotoSuite\Application\Service;

use PHPhotoSuit\PhotoSuite\Application\Service\ThumbFinder;
use PHPhotoSuit\PhotoSuite\Application\Service\ThumbFinderRequest;
use PHPhotoSuit\PhotoSuite\Application\Service\ThumbRequest;
use PHPhotoSuit\PhotoSuite\Application\Service\ThumbRequestCollection;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoThumbMode;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoThumbSize;
use PHPhotoSuit\PhotoSuite\Domain\ResourceId;
use PHPhotoSuit\PhotoSuite\Infrastructure\Presenter\ArrayPhotoPresenter;
use PHPhotoSuit\PhotoSuite\Infrastructure\Presenter\ArrayThumbPresenter;
use PHPhotoSuit\PhotoSuite\Infrastructure\Storage\LocalStorageConfig;
use PHPhotoSuit\PhotoSuite\Infrastructure\Storage\PhotoLocalStorage;

class ThumbFinderTest extends \PHPUnit_Framework_TestCase
{
    /** @var ThumbFinder */
    private $thumbFinder;
    /** @var string */
    private $storagePath = __DIR__ . '/../../Infrastructure/Storage/storage_test_folder';
    /** @var string */
    private $httpUrlBasePath = 'http://test';
    /** @var LocalStorageConfig */
    private $localStorageConfig;

    public function setUp()
    {
        $this->localStorageConfig = new LocalStorageConfig($this->storagePath, $this->httpUrlBasePath);
        $this->thumbFinder = new ThumbFinder(
            new InMemoryPhotoRepository(),
            new InMemoryThumbRepository(),
            new InMemoryPhotoThumbGenerator(),
            new PhotoLocalStorage($this->localStorageConfig),
            new ArrayThumbPresenter(new ArrayPhotoPresenter())
        );
    }

    /**
     * @test
     */
    public function findPhotoThumbsOf()
    {
        $thumbRequestCollection = new ThumbRequestCollection();
        $thumbRequestCollection[] = new ThumbRequest(new PhotoThumbSize(1, 1));
        $request = new ThumbFinderRequest(new ResourceId(2), $thumbRequestCollection);
        $response = $this->thumbFinder->findPhotoThumbsOf($request);

        $this->assertArrayHasKey('thumbs', $response);
        $this->assertTrue(count($response['thumbs']) === 1);
    }

    /**
     * @test
     */
    public function findPhotoCollectionWithItsThumbsOf()
    {
        $thumbRequestCollection = new ThumbRequestCollection();
        $thumbRequestCollection[] = new ThumbRequest(new PhotoThumbSize(1, 1));
        $request = new ThumbFinderRequest(new ResourceId(1), $thumbRequestCollection);

        $response = $this->thumbFinder->findPhotoCollectionWithItsThumbsOf($request);

        $this->assertCount(2, $response);
    }
}
