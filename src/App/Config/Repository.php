<?php

namespace PHPhotoSuit\App\Config;

class Repository extends ConfigElement
{
    const REPOSITORY_SQLITE = 'sqlite';
    const REPOSITORY_MYSQL = 'mysql';
    
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
