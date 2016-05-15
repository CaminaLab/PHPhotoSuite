<?php

namespace PHPhotoSuit\PhotoSuite\Domain;

use PHPhotoSuit\PhotoSuite\Domain\Exception\FileNotFoundException;

class File
{
    /** @var string */
    private $path;

    /**
     * @param string $path
     * @throws FileNotFoundException
     */
    public function __construct($path)
    {
        if (!file_exists($path)) {
            throw new FileNotFoundException(sprintf('File %s not found.', $path));
        }
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function path()
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function format()
    {
        return pathinfo($this->path, PATHINFO_EXTENSION);
    }
}
