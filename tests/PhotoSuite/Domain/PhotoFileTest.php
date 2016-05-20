<?php

namespace PHPhotoSuit\Tests\PhotoSuite\Domain;

use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoFile;

class PhotoFileTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function photoFileInstanceWorks()
    {
        $photoFile = new PhotoFile(__DIR__ . '/pixel.png');
        $this->assertEquals('png', $photoFile->format());
        $this->assertEquals(__DIR__ . '/pixel.png', $photoFile->filePath());
    }
}
