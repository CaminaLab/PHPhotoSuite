<?php

namespace PHPhotoSuit\PhotoSuite\Infrastructure\Storage;

class LocalStorageConfig
{
    /** @var string */
    private $storagePath;
    /** @var string */
    private $urlBase;

    /**
     * @param string $storagePath
     * @param string $urlBase
     * @throws \Exception
     */
    public function __construct($storagePath, $urlBase)
    {
        if (!file_exists($storagePath)) {
            throw new \Exception(sprintf('Storage path %s does not exists', $storagePath));
        }
        $this->storagePath = realpath($storagePath);
        $this->urlBase = $urlBase;
    }

    /**
     * @return string
     */
    public function storagePath()
    {
        return $this->storagePath;
    }

    /**
     * @return string
     */
    public function urlBase()
    {
        return $this->urlBase;
    }
}
