<?php

namespace PHPhotoSuit\PhotoSuite\Domain\Model;

use PHPhotoSuit\PhotoSuite\Domain\RandomIdGenerator;

class ThumbId
{
    /** @var string */
    private $id;

    /**
     * PhotoId constructor.
     * @param string $id
     */
    public function __construct($id = null)
    {
        if (is_null($id)) {
            $this->id = RandomIdGenerator::getBase36(8);
        } else if (RandomIdGenerator::isValidBase36($id, 8)) {
            $this->id = $id;
        } else {
            throw new \InvalidArgumentException('Invalid uuid');
        }
    }

    /**
     * @return string
     */
    public function id()
    {
        return $this->id;
    }
}
