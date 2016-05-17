<?php

namespace PHPhotoSuit\Tests\PhotoSuite\Domain;

use PHPhotoSuit\PhotoSuite\Domain\HttpUrl;
use PHPhotoSuit\PhotoSuite\Domain\Photo;
use PHPhotoSuit\PhotoSuite\Domain\PhotoFile;
use PHPhotoSuit\PhotoSuite\Domain\PhotoFormat;
use PHPhotoSuit\PhotoSuite\Domain\PhotoId;
use PHPhotoSuit\PhotoSuite\Domain\PhotoName;
use PHPhotoSuit\PhotoSuite\Domain\ResourceId;

class PhotoTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function photoWorks() {
        $photoFile = new PhotoFile(__DIR__ . '/pixel.png');
        $photoId = new PhotoId();
        $photo = new Photo(
            $photoId,
            new ResourceId(1),
            new PhotoName('testing'),
            new HttpUrl('http://works'),
            $photoFile
        );
        $this->assertEquals($photoId->id(), $photo->id());
        $this->assertEquals(1, $photo->resourceId());
        $this->assertEquals('testing', $photo->name());
        $this->assertEquals('testing', $photo->slug());
        $this->assertEquals('http://works', $photo->getPhotoHttpUrl());
        $this->assertSame($photoFile, $photo->photoFile());
    }
}