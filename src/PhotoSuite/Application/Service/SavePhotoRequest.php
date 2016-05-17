<?php

namespace PHPhotoSuit\PhotoSuite\Application\Service;

class SavePhotoRequest
{
    /** @var string */
    private $resourceId;
    /** @var string */
    private $name;
    /** @var string */
    private $file;

    /**
     * @param string $resourceId
     * @param string $name
     * @param string $file
     */
    public function __construct($resourceId, $name, $file)
    {
        $this->resourceId = $resourceId;
        $this->name = $name;
        $this->file = $file;
    }

    /**
     * @return string
     */
    public function resourceId()
    {
        return $this->resourceId;
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function file()
    {
        return $this->file;
    }
}
