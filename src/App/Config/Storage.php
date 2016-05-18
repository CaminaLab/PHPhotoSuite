<?php

namespace PHPhotoSuit\App\Config;

class Storage extends ConfigElement
{
    /**
     * Storage constructor.
     * @param string $driver
     * @param array $config
     */
    public function __construct($driver = 'local', array $config = [])
    {
        $this->driver = $driver;
        $this->config = $config;
    }
}
