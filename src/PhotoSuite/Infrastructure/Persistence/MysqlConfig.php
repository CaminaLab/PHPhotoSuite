<?php

namespace PHPhotoSuit\PhotoSuite\Infrastructure\Persistence;

class MysqlConfig
{
    /** @var string */
    private $host;
    /** @var int */
    private $port;
    /** @var string */
    private $dbName;
    /** @var string */
    private $user;
    /** @var string */
    private $password;

    /**
     * @param string $host
     * @param string $dbName
     * @param string $user
     * @param string $password
     * @param int $port
     */
    public function __construct($host, $dbName, $user, $password, $port=3306)
    {
        $this->host = $host;
        $this->dbName = $dbName;
        $this->user = $user;
        $this->password = $password;
        $this->port = $port;
    }

    public static function getInstanceByArray($config)
    {
        if (!isset($config['port'])){
            return new self($config['host'], $config['dbname'], $config['user'], $config['password'], $config['port']);
        } else {
            return new self($config['host'], $config['dbname'], $config['user'], $config['password']);
        }
    }

    /**
     * @return string
     */
    public function host()
    {
        return $this->host;
    }

    /**
     * @return int
     */
    public function port()
    {
        return $this->port;
    }

    /**
     * @return string
     */
    public function dbName()
    {
        return $this->dbName;
    }

    /**
     * @return string
     */
    public function user()
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function password()
    {
        return $this->password;
    }
}
