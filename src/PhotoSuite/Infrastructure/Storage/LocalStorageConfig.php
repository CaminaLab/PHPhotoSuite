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
            throw new \InvalidArgumentException(sprintf('Storage path %s does not exists', $storagePath));
        }
        $this->storagePath = realpath($storagePath);
        $this->urlBase = $urlBase;
    }

    /**
     * @param array $config
     * @return LocalStorageConfig
     */
    public static function getInstanceByArray(array $config)
    {
        return new self($config['storagePath'], $config['baseUrl']);
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
