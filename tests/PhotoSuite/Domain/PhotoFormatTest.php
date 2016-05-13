<?php

namespace PHPhotoSuit\Tests\PhotoSuite\Domain;

use PHPhotoSuit\PhotoSuite\Domain\PhotoFormat;
use PHPhotoSuit\PhotoSuite\Domain\Exception\InvalidFormatException;

class PhotoFormatTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider formatsProvider
     */
    public function validFormatOfPhotos($value)
    {
        $format = new PhotoFormat($value);
        $this->assertTrue($format->value() === $value);
    }
    
    public function formatsProvider() {
        return [
            ['jpeg'],
            ['jpg'],
            ['png'],
            ['gif'],
        ];
    }

    /**
     * @test
     */
    public function exception()
    {
        $this->expectException(InvalidFormatException::class);
        new PhotoFormat('invalid');
    }
}
