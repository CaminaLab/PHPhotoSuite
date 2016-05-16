<?php

namespace PHPhotoSuit\PhotoSuite\Domain;

class PhotoFile
{
    /** @var PhotoFormat */
    private $format;
    /** @var File */
    private $file;

    /**
     * @param PhotoFormat $format
     */
    public function __construct(PhotoFormat $format)
    {
        $this->format = $format;
    }

    /**
     * @param $filePath
     * @return PhotoFile
     */
    public static function getInstanceBy($filePath)
    {
        $file = new File($filePath);
        $instance = new self(new PhotoFormat($file->format()));
        $instance->file = $file;

        return $instance;
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
