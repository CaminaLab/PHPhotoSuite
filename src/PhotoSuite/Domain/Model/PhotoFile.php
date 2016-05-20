<?php

namespace PHPhotoSuit\PhotoSuite\Domain\Model;

use PHPhotoSuit\PhotoSuite\Domain\File;

class PhotoFile
{
    /** @var PhotoFormat */
    private $format;
    /** @var File */
    private $file;

    /**
     * @param string $filePath
     */
    public function __construct($filePath)
    {
        $this->file = new File($filePath);
        $this->format = new PhotoFormat($this->file->format());
    }

    /**
     * @return string
     */
    public function format()
    {
        return $this->format->value();
    }

    /**
     * @return string
     */
    public function filePath()
    {
        return $this->file->filePath();
    }
}
