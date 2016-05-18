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
            throw new \Exception(sprintf('File %s not found', $dbPath));
        }
        $this->dbPath = $dbPath;
    }

    /**
     * @return string
     */
    public function dbPath()
    {
        return $this->dbPath;
    }
}
