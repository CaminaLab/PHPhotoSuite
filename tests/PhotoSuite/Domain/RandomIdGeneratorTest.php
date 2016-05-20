<?php

namespace PHPhotoSuit\Tests\PhotoSuite\Domain;

use PHPhotoSuit\PhotoSuite\Domain\RandomIdGenerator;

class RandomIdGeneratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function getBase62IdWorks()
    {
        $id = RandomIdGenerator::getBase62(5);
        $this->assertTrue(RandomIdGenerator::isValidBase62($id, 5));
    }
    /**
     * @test
     */
    public function getBase36IdWorks()
    {
        $id = RandomIdGenerator::getBase36(8);
        $this->assertTrue(RandomIdGenerator::isValidBase62($id, 8));
    }

    /**
     * @test
     */
    public function testInvalidIds()
    {
        $this->assertFalse(RandomIdGenerator::isValidBase62('', 2));
        $this->assertFalse(RandomIdGenerator::isValidBase36('', 2));
        $this->assertFalse(RandomIdGenerator::isValidBase36('aaaaa', 5));
    }
}
