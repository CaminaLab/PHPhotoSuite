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
     * @param $path
     * @return PhotoFile
     */
    public static function getInstanceByPath($path)
    {
        $file = new File($path);
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
    public function getFile()
    {
        return $this->file->path();
    }
}
