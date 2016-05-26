<?php

namespace PHPhotoSuit\PhotoSuite\Infrastructure\Persistence;

class SqlitePDORegistry
{
    /**
     * @var \PDO[]
     */
    private static $instances = [];

    /**
     * @param string $dbPath
     * @return \PDO
     */
    public static function getInstance($dbPath)
    {
        if (!isset(self::$instances[$dbPath])) {
            self::$instances[$dbPath] = new \PDO('sqlite:'.$dbPath);
        }
        return self::$instances[$dbPath];
    }
    
    public static function removeInstance($dbPath)
    {
        unset(self::$instances[$dbPath]);
    }
}
