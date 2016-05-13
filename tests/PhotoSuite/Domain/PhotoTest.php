<?php

namespace PHPhotoSuit\Tests\PhotoSuite\Domain;

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
            new PhotoFormat('jpg')
        );
        $this->assertEquals($photo->resourceId(), 1);
        $this->assertEquals($photo->format(), 'jpg');
        $this->assertEquals($photo->name(), 'testing');
        $this->assertEquals($photo->slug(), 'testing');
    }
}