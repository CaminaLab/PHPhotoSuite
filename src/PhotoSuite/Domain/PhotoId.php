<?php

namespace PHPhotoSuit\PhotoSuite\Domain;

use Ramsey\Uuid\Uuid;

class PhotoId
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
            $this->id = Uuid::uuid1()->toString();
        } else if (Uuid::isValid($id)) {
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
