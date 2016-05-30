<?php

namespace PHPhotoSuit\App\Config;

class Storage extends ConfigElement
{
    const STORAGE_LOCAL = 'local';
    const STORAGE_S3_AMAZON = 's3amazon';
    
    /**
     * Storage constructor.
     * @param string $driver
     * @param array $config
     */
    public function __construct($driver = self::STORAGE_LOCAL, array $config = [])
    {
        $this->driver = $driver;
        $this->config = $config;
    }
}
