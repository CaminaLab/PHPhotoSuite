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

    /**
     * @param string $resourceId
     * @param string $name
     * @param string $file
     * @param string $alt
     * @param string $lang
     */
    public function __construct($resourceId, $name, $file, $alt, $lang)
    {
        $this->resourceId = $resourceId;
        $this->name = $name;
        $this->file = $file;
        $this->alt = $alt;
        $this->lang = $lang;
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
}
