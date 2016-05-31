<?php

namespace PHPhotoSuit\PhotoSuite\Infrastructure\Persistence;

class MysqlPDORegistry
{

    /**
     * @var \PDO[]
     */
    private static $instances = [];

    public static function getInstance(MysqlConfig $config)
    {
        $key = md5(serialize($config));
        if (!isset(self::$instances[$key])) {
            self::$instances[$key] = new  \PDO(
                'mysql:host='.$config->host().';port='.$config->port().';dbname='.$config->dbName(),
                $config->user(),
                $config->password()
            );
        }
        return self::$instances[$key];
    }
}
