<?php

namespace PHPhotoSuit\Tests\PhotoSuite\Domain;

use PHPhotoSuit\PhotoSuite\Domain\Exception\InvalidHttpUrl;
use PHPhotoSuit\PhotoSuite\Domain\HttpUrl;

class HttpUrlTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider validHttpUrls
     * @param $url
     * @param $expected
     */
    public function httpUrlWorks($url, $expected)
    {
        $httpUrl = new HttpUrl($url);
        $this->assertSame($expected, $httpUrl->value());
    }


    public function validHttpUrls()
    {
        return [
            ['http://works','http://works'],
            ['https://works','https://works'],
            ['https://works.com/','https://works.com'],
        ];
    }

    /**
     * @test
     * @dataProvider invalidHttpUrls
     * @param $url
     */
    public function httpUrlThrowsException($url)
    {
        $this->expectException(InvalidHttpUrl::class);
        new HttpUrl($url);
    }


    public function invalidHttpUrls()
    {
        return [
            ['http://'],
            [''],
        ];
    }

}
