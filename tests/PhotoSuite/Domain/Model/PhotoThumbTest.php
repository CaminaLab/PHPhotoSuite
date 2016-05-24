<?php

namespace PHPhotoSuit\Tests\PhotoSuite\Domain\Model;

use PHPhotoSuit\PhotoSuite\Domain\HttpUrl;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoFile;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoId;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoThumb;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoThumbMode;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoThumbSize;
use PHPhotoSuit\PhotoSuite\Domain\Model\ThumbId;

class PhotoThumbTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function photoThumbWorks()
    {
        $thumbId = new ThumbId();
        $photoId = new PhotoId();
        $photoFile = new PhotoFile(__DIR__ . '/pixel.png');
        $photoThumb = new PhotoThumb(
            $thumbId,
            $photoId,
            new HttpUrl('http://works'),
            new PhotoThumbSize(10,10),
            new PhotoThumbMode(PhotoThumbMode::THUMBNAIL_OUTBOUND),
            $photoFile
        );
        $this->assertEquals($thumbId->id(), $photoThumb->id());
        $this->assertEquals($photoId->id(), $photoThumb->photoId());
        $this->assertEquals('http://works', $photoThumb->photoThumbHttpUrl());
        $this->assertEquals(10, $photoThumb->height());
        $this->assertEquals(10, $photoThumb->width());
        $this->assertEquals(PhotoThumbMode::THUMBNAIL_OUTBOUND, $photoThumb->mode());
        $this->assertEquals($photoFile->filePath(), __DIR__ . '/pixel.png');

        $photoThumb->updatePhotoThumbFile(null);
        $this->assertNull($photoThumb->photoThumbFile());
    }
}
