<?php

namespace PHPhotoSuit\Tests\PhotoSuite\Domain\Model;

use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoThumbSize;

class PhotoThumbSizeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function photoThumbSizeWorks()
    {
        $thumbSize = new PhotoThumbSize(10,10);
        $this->assertInstanceOf(PhotoThumbSize::class, $thumbSize);
        $this->assertSame(10, $thumbSize->height());
        $this->assertSame(10, $thumbSize->width());
    }

    /**
     * @test
     * @dataProvider getExceptionSizes
     * @param $height
     * @param $width
     */
    public function exceptions($height, $width)
    {
        $this->expectException(\InvalidArgumentException::class);
        new PhotoThumbSize($height, $width);
    }

    public function getExceptionSizes()
    {
        return [
            [0,1],
            [1,0],
            [1,-1],
            [-1,1],
            ['',1],
            [1,''],
        ];
    }
}
