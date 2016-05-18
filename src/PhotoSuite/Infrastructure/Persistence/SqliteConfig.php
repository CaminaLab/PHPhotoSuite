<?php

namespace PHPhotoSuit\PhotoSuite\Infrastructure\Persistence;

class SqliteConfig
{
    /** @var string */
    private $dbPath;

    /**
     * @param string $dbPath
     * @throws \Exception
     */
    public function __construct($dbPath)
    {
        if (!file_exists($dbPath)) {
            throw new \InvalidArgumentException(sprintf('Sqlite DB file %s not found', $dbPath));
        }
        $this->dbPath = $dbPath;
    }

    /**
     * @param array $config
     * @return SqliteConfig
     */
    public static function getInstanceByArray(array $config)
    {
        return new self($config['dbpath']);
    }

    /**
     * @return string
     */
    public function dbPath()
    {
        return $this->dbPath;
    }
}
