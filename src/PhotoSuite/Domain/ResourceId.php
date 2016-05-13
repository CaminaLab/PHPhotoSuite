<?php

namespace PHPhotoSuit\PhotoSuite\Domain;

use PHPhotoSuit\PhotoSuite\Domain\Exception\InvalidResourceIdException;

class ResourceId
{
    /** @var string */
    private $id;

    /**
     * @param string $id
     * @throws InvalidResourceIdException
     */
    public function __construct($id)
    {
        if (!empty($id) && is_numeric($id)) {
            $id = (string)$id;
        }
        if (empty($id) || !is_string($id)) {
            throw new InvalidResourceIdException();
        }
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function id()
    {
        return $this->id;
    }
}
