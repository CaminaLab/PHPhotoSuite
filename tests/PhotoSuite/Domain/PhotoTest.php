<?php

namespace PHPhotoSuit\Tests\PhotoSuite\Domain;

use PHPhotoSuit\PhotoSuite\Domain\HttpUrl;
use PHPhotoSuit\PhotoSuite\Domain\Photo;
use PHPhotoSuit\PhotoSuite\Domain\PhotoFormat;
use PHPhotoSuit\PhotoSuite\Domain\PhotoName;
use PHPhotoSuit\PhotoSuite\Domain\ResourceId;

class PhotoTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function photoWorks() {
        $photo = new Photo(
            new ResourceId(1),
            new PhotoName('testing'),
            new PhotoFormat('jpg'),
            new HttpUrl('http://works')
        );
        $this->assertEquals(1, $photo->resourceId());
        $this->assertEquals('testing', $photo->name());
        $this->assertEquals('http://works/1/testing.jpg', $photo->getPhotoHttpUrl());
    }
}