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
    /** @var string */
    private $alt;
    /** @var string */
    private $lang;
    /** @var integer */
    private $position;

    /**
     * @param string $resourceId
     * @param string $name
     * @param string $file
     * @param string $alt
     * @param string $lang
     * @param int $position
     */
    public function __construct($resourceId, $name, $file, $alt, $lang, $position = 9999)
    {
        $this->resourceId = $resourceId;
        $this->name = $name;
        $this->file = $file;
        $this->alt = $alt;
        $this->lang = $lang;
        $this->position = $position;
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

    /**
     * @return string
     */
    public function alt()
    {
        return $this->alt;
    }

    /**
     * @return string
     */
    public function lang()
    {
        return $this->lang;
    }

    /**
     * @return int
     */
    public function position()
    {
        return $this->position;
    }
}
