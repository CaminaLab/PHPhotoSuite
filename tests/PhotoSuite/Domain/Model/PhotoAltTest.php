<?php

namespace PHPhotoSuit\Tests\PhotoSuite\Domain\Model;

use PHPhotoSuit\PhotoSuite\Domain\Exception\InvalidLengthException;
use PHPhotoSuit\PhotoSuite\Domain\Lang;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoAlt;

class PhotoAltTest extends  \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function photoNameCanBeInstantiated()
    {
        $alt = 'testing name';
        $altSlug = 'testing-name';
        $photoAlt = new PhotoAlt($alt, new Lang(Lang::LANGUAGE_ES));
        $this->assertEquals($alt, $photoAlt->name());
        $this->assertEquals($altSlug, $photoAlt->slug());
        $this->assertEquals(Lang::LANGUAGE_ES, $photoAlt->lang());
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
        new PhotoAlt($longString, new Lang(Lang::LANGUAGE_ES));
    }
}
