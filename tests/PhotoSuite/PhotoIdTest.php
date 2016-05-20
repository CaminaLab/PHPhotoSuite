<?php

namespace PHPhotoSuit\Tests\PhotoSuite;

use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoId;
use Ramsey\Uuid\Uuid;

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
        $this->assertInstanceOf(PhotoId::class, new PhotoId(Uuid::uuid1()->toString()));
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
