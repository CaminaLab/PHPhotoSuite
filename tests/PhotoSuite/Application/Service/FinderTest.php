<?php

namespace PHPhotoSuit\Tests\PhotoSuite\Application\Service;

use PHPhotoSuit\PhotoSuite\Application\Service\Finder;
use PHPhotoSuit\PhotoSuite\Domain\ResourceId;
use PHPhotoSuit\PhotoSuite\Infrastructure\Presenter\ArrayPhotoPresenter;

class FinderTest extends \PHPUnit_Framework_TestCase
{
    /** @var Finder */
    private $finder;

    public function setUp()
    {
        $this->finder = new Finder(new InMemoryPhotoRepository(), new ArrayPhotoPresenter());
    }

    /**
     * @test
     */
    public function findPhotoOf()
    {
        $this->assertTrue(is_array($this->finder->findPhotoOf(new ResourceId(2))));
    }

    /**
     * @test
     */
    public function findPhotoCollectionOf()
    {
        $this->assertTrue(count($this->finder->findPhotoCollectionOf(new ResourceId(1))) === 2);
    }
}
