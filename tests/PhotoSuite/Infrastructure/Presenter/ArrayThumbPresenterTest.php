<?php

namespace PHPhotoSuit\Tests\PhotoSuite\Infrastructure\Presenter;

use PHPhotoSuit\PhotoSuite\Domain\HttpUrl;
use PHPhotoSuit\PhotoSuite\Domain\Model\CollectionOfThumbCollection;
use PHPhotoSuit\PhotoSuite\Domain\Model\Photo;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoCollection;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoId;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoPresenter;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoThumb;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoThumbCollection;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoThumbMode;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoThumbSize;
use PHPhotoSuit\PhotoSuite\Domain\Model\ThumbId;
use PHPhotoSuit\PhotoSuite\Infrastructure\Presenter\ArrayThumbPresenter;

class ArrayThumbPresenterTest extends \PHPUnit_Framework_TestCase
{
    /** @var ArrayThumbPresenter */
    private $arrayThumbPresenter;
    /** @var array */
    private $expected;
    /** @var PhotoThumb */
    private $thumb;
    /** @var PhotoThumbCollection */
    private $thumbCollection;

    public function setUp()
    {
        $this->arrayThumbPresenter = new ArrayThumbPresenter(
            $this->getPhotoPresenterMock()
        );
        $thumbId = new ThumbId();
        $url = new HttpUrl('http://test');
        $thumbSize = new PhotoThumbSize(15, 10);
        $this->expected = [
            'thumbs' => [
                0 => [
                    'id' => $thumbId->id(),
                    'url' => $url->value(),
                    'height' => $thumbSize->height(),
                    'width' => $thumbSize->width()
                ]
            ]
        ];

        $this->thumb  = new PhotoThumb($thumbId, new PhotoId(), $url, $thumbSize);

        $this->thumbCollection = new PhotoThumbCollection([$this->thumb]);
    }

    /**
     * @test
     */
    public function write()
    {
        $this->assertEquals(
            $this->expected,
            $this->arrayThumbPresenter->write(
                $this->getMockBuilder(Photo::class)->disableOriginalConstructor()->getMock(), $this->thumbCollection
            )
        );
    }

    /**
     * @test
     */
    public function writeCollection()
    {
        $this->assertEquals(
            [$this->expected],
            $this->arrayThumbPresenter->writeCollection(
                new PhotoCollection([$this->getMockBuilder(Photo::class)->disableOriginalConstructor()->getMock()]),
                new CollectionOfThumbCollection([$this->thumbCollection])
            )
        );
    }
    /**
     * @return PhotoPresenter
     */
    private function getPhotoPresenterMock()
    {
        $mock = $this->getMockBuilder(PhotoPresenter::class)->getMock();
        $mock->method('write')->willReturn([]);

        return $mock;
    }
}
