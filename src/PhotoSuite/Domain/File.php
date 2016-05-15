<?php

namespace PHPhotoSuit\PhotoSuite\Domain;

use PHPhotoSuit\PhotoSuite\Domain\Exception\FileNotFoundException;

class File
{
    /** @var string */
    private $filePath;

    /**
     * @param string $filePath
     * @throws FileNotFoundException
     */
    public function __construct($filePath)
    {
        if (!file_exists($filePath)) {
            throw new FileNotFoundException(sprintf('File %s not found.', $filePath));
        }
        $this->filePath = $filePath;
    }

    /**
     * @return string
     */
    public function filePath()
    {
        return $this->filePath;
    }

    /**
     * @return string
     */
    public function format()
    {
        return pathinfo($this->filePath, PATHINFO_EXTENSION);
    }
}
