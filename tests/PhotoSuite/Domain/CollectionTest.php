<?php

namespace PHPhotoSuit\Tests\PhotoSuite\Domain;

use PHPhotoSuit\PhotoSuite\Domain\Collection;

class CollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function newCollectionMustThrowLogicException()
    {
        $this->expectException(\LogicException::class);
        new Collection();
    }
}
