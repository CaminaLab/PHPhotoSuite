<?php

namespace PHPhotoSuit\Tests\PhotoSuite\Infrastructure\ThumbGenerator;

use Imagine\Gd\Imagine;
use PHPhotoSuit\PhotoSuite\Domain\HttpUrl;
use PHPhotoSuit\PhotoSuite\Domain\Model\Photo;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoAltCollection;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoFile;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoId;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoName;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoThumbSize;
use PHPhotoSuit\PhotoSuite\Domain\Model\ThumbId;
use PHPhotoSuit\PhotoSuite\Domain\Position;
use PHPhotoSuit\PhotoSuite\Domain\ResourceId;
use PHPhotoSuit\PhotoSuite\Infrastructure\ThumbGenerator\ImaginePhotoThumbGenerator;
use PHPhotoSuit\PhotoSuite\Infrastructure\ThumbGenerator\ThumbGeneratorConfig;

class ImaginePhotoThumbGeneratorTest extends \PHPUnit_Framework_TestCase
{
    /** @var ImaginePhotoThumbGenerator */
    private $thumbGenerator;
    /** @var string */
    private $driver = 'gd';
    /** @var string */
    private $tmpPath = __DIR__ . '/tmp_test';
    /** @var Imagine */
    private $imagine;

    protected function setUp()
    {
        $this->thumbGenerator = new ImaginePhotoThumbGenerator(new ThumbGeneratorConfig($this->driver, $this->tmpPath));
        $this->imagine = new Imagine();
    }

    /**
     * @test
     * @dataProvider photos
     * @param $photo
     */
    public function generate($photo)
    {
        $photoThumb = $this->thumbGenerator->generate(
            new ThumbId(),
            $photo,
            new PhotoThumbSize(95, 95),
            new HttpUrl('http://test')
        );
        $image = $this->imagine->open($photoThumb->photoThumbFile()->filePath());

        $this->assertEquals(95, $image->getSize()->getHeight());
        $this->assertEquals(95, $image->getSize()->getWidth());
        unlink($photoThumb->photoThumbFile()->filePath());
    }

    public function photos()
    {
        return [
            [new Photo(
                new PhotoId(),
                new ResourceId(1),
                new PhotoName('test'),
                new HttpUrl('http://test'),
                new PhotoAltCollection(),
                new Position(),
                new PhotoFile(__DIR__ . '/landscape640x480.png')
            )],
            [new Photo(
                new PhotoId(),
                new ResourceId(1),
                new PhotoName('test'),
                new HttpUrl('http://test'),
                new PhotoAltCollection(),
                new Position(),
                new PhotoFile(__DIR__ . '/portrait480x640.png')
            )]
        ];
    }
}
