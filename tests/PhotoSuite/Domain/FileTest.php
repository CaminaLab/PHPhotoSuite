<?php

namespace PHPhotoSuit\Tests\PhotoSuite\Domain;

use PHPhotoSuit\PhotoSuite\Domain\Exception\FileNotFoundException;
use PHPhotoSuit\PhotoSuite\Domain\File;

class FileTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function fileWorks()
    {
        $file = new File(__DIR__ . '/filetest.txt');
        $this->assertEquals(__DIR__ . '/filetest.txt', $file->filePath());
        $this->assertEquals('txt', $file->format());
    }

    /**
     * @test
     */
    public function exceptions()
    {
        $this->expectException(FileNotFoundException::class);
        new File('');
    }
}
