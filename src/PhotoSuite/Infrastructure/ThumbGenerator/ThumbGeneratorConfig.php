<?php

namespace PHPhotoSuit\PhotoSuite\Infrastructure\ThumbGenerator;

class ThumbGeneratorConfig
{
    /** @var  string */
    private $driver;
    /** @var string */
    private $tempPath;

    /**
     * ThumbGeneratorConfig constructor.
     * @param string $driver
     * @param string $tempPath
     */
    public function __construct($driver = 'gd', $tempPath = '/tmp')
    {
        if (!file_exists($tempPath)) {
            throw new \InvalidArgumentException('Invalid path: ' . $tempPath);
        }
        $this->driver = $driver;
        $this->tempPath = realpath($tempPath);
    }

    /**
     * @return string
     */
    public function driver()
    {
        return $this->driver;
    }

    /**
     * @return string
     */
    public function tempPath()
    {
        return $this->tempPath;
    }
}
