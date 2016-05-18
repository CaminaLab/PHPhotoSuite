<?php

namespace PHPhotoSuit\App\Config;

class ConfigElement
{
    /** @var sring */
    protected $driver = '';
    /** @var array */
    protected $config = [];

    /**
     * @return sring
     */
    public function driver()
    {
        return $this->driver;
    }

    /**
     * @return array
     */
    public function config()
    {
        return $this->config;
    }
}
