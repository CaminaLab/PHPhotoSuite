<?php

namespace PHPhotoSuit\Tests\PhotoSuite\Infrastructure\Presenter;

use PHPhotoSuit\PhotoSuite\Domain\HttpUrl;
use PHPhotoSuit\PhotoSuite\Domain\Photo;
use PHPhotoSuit\PhotoSuite\Domain\PhotoCollection;
use PHPhotoSuit\PhotoSuite\Domain\PhotoFile;
use PHPhotoSuit\PhotoSuite\Domain\PhotoFormat;
use PHPhotoSuit\PhotoSuite\Domain\PhotoId;
use PHPhotoSuit\PhotoSuite\Domain\PhotoName;
use PHPhotoSuit\PhotoSuite\Domain\ResourceId;
use PHPhotoSuit\PhotoSuite\Infrastructure\Presenter\ArrayPhotoPresenter;

class ArrayPhotoPresenterTest extends \PHPUnit_Framework_TestCase
{
    /** @var Photo */
    private $photo;
    /** @var ArrayPhotoPresenter */
    private $photoPresenter;
    /** @var array */
    private $expected;

    public function setUp()
    {
        $photoId = new PhotoId();
        $this->expected = [
            'id' => $photoId->id(),
            'resourceId' => '1',
            'name' => 'test',
            'url' => 'http://test/1/test.jpg'
        ];
        $this->photo = new Photo(
            $photoId,
            new ResourceId('1'),
            new PhotoName('test'),
            new HttpUrl('http://test/1/test.jpg')
        );
        $this->photoPresenter = new ArrayPhotoPresenter();
    }

    /**
     * @test
     */
    public function writeReturnsExpected()
    {
        $this->assertEquals($this->expected, $this->photoPresenter->write($this->photo));
    }

    /**
     * @test
     */
    public function writeCollectionReturnsExpected()
    {
        $this->assertEquals(
            [$this->expected],
            $this->photoPresenter->writeCollection(new PhotoCollection([$this->photo]))
        );
    }
}
