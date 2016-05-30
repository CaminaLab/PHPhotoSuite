<?php

namespace PHPhotoSuit\PhotoSuite\Infrastructure\Storage;

class AmazonS3Config
{

    /** @var string */
    private $bucket;
    /** @var string */
    private $urlBase;
    /** @var string */
    private $key;
    /** @var string */
    private $secret;

    /**
     * AmazonS3Config constructor.
     * @param string $bucket
     * @param string $urlBase
     * @param string $key
     * @param string $secret
     */
    public function __construct($bucket, $urlBase, $key, $secret)
    {
        $this->bucket = $bucket;
        $this->urlBase = $urlBase;
        $this->key = $key;
        $this->secret = $secret;
    }


    /**
     * @param $config
     * @return AmazonS3Config
     */
    public static function getInstanceByArray(array $config)
    {
        return new self($config['bucket'], $config['urlBase'], $config['key'], $config['secret']);
    }

    /**
     * @return string
     */
    public function bucket()
    {
        return $this->bucket;
    }

    /**
     * @return string
     */
    public function urlBase()
    {
        return $this->urlBase;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function getSecret()
    {
        return $this->secret;
    }
}
