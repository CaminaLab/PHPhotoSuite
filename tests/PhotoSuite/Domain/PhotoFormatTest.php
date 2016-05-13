<?php

namespace PHPhotoSuit\Tests\PhotoSuite\Domain;

use PHPhotoSuit\PhotoSuite\Domain\PhotoFormat;

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
     * @expectedException PHPhotoSuit\PhotoSuite\Domain\Exception\InvalidFormatException
     */
    public function exception()
    {
        new PhotoFormat('invalid');
    }
}
