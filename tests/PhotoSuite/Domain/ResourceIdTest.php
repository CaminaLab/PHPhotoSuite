<?php

namespace PHPhotoSuit\Tests\PhotoSuite\Domain;

use PHPhotoSuit\PhotoSuite\Domain\Exception\InvalidResourceIdException;
use PHPhotoSuit\PhotoSuite\Domain\ResourceId;

class ResourceIdTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @param $value
     * @dataProvider exceptionValues
     */
    public function exceptions($value)
    {
        $this->expectException(InvalidResourceIdException::class);
        new ResourceId($value);
    }

    public function exceptionValues()
    {
        return [
            [''],
            [0],
            ['0'],
            [false],
            [null],
            [[]],
        ];
    }
}
