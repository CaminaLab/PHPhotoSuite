<?php

namespace PHPhotoSuit\Tests\PhotoSuite\Domain\Model;

use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoId;
use PHPhotoSuit\PhotoSuite\Domain\Model\ThumbId;
use PHPhotoSuit\PhotoSuite\Domain\RandomIdGenerator;

class ThumbIdTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function PhotoIdWorks()
    {
        $this->assertInstanceOf(ThumbId::class, new ThumbId());
    }

    /**
     * @test
     */
    public function PhotoIdWithUuidWorks()
    {
        $this->assertInstanceOf(ThumbId::class, new ThumbId(RandomIdGenerator::getBase36(8)));
    }

    /**
     * @test
     */
    public function exception()
    {
        $this->expectException(\InvalidArgumentException::class);
        new ThumbId('invalid_id');
    }

}
