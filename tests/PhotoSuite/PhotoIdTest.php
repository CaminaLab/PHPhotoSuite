<?php

namespace PHPhotoSuit\Tests\PhotoSuite;

use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoId;
use PHPhotoSuit\PhotoSuite\Domain\RandomIdGenerator;

class PhotoIdTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function PhotoIdWorks()
    {
        $this->assertInstanceOf(PhotoId::class, new PhotoId());
    }

    /**
     * @test
     */
    public function PhotoIdWithUuidWorks()
    {
        $this->assertInstanceOf(PhotoId::class, new PhotoId(RandomIdGenerator::getBase36(8)));
    }

    /**
     * @test
     */
    public function exception()
    {
        $this->expectException(\InvalidArgumentException::class);
        new PhotoId('invalid_id');
    }

}
