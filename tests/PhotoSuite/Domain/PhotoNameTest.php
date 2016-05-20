<?php

namespace PHPhotoSuit\Tests\PhotoSuite\Domain;

use PHPhotoSuit\PhotoSuite\Domain\Exception\InvalidLengthException;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoName;

class PhotoNameTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function photoNameCanBeInstantiated()
    {
        $name = 'testing name';
        $nameSlug = 'testing-name';
        $photoName = new PhotoName($name);
        $this->assertTrue($photoName->name() === $name);
        $this->assertTrue($photoName->slug() === $nameSlug);
    }

    /**
     * @test
     */
    public function exceptions()
    {
        $this->expectException(InvalidLengthException::class);
        $longString = '';
        for ($i=0; $i<256; $i++) {
            $longString .= 'a';
        }
        new PhotoName($longString);
    }
}
