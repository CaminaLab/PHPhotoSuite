<?php

namespace PHPhotoSuit\App\Config;

class Repository extends ConfigElement
{
    /**
     * Repository constructor.
     * @param $driver
     * @param $config
     */
    public function __construct($driver = 'sqlite', array $config = [])
    {
        $this->driver = $driver;
        $this->config = $config;
    }
}
