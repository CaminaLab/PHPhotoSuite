<?php

namespace PHPhotoSuit\App\Config;

use PHPhotoSuit\PhotoSuite\Infrastructure\Persistence\SqliteConfig;
use PHPhotoSuit\PhotoSuite\Infrastructure\Persistence\SqlitePhotoRepository;
use PHPhotoSuit\PhotoSuite\Infrastructure\Presenter\ArrayPhotoPresenter;
use PHPhotoSuit\PhotoSuite\Infrastructure\Storage\LocalStorageConfig;
use PHPhotoSuit\PhotoSuite\Infrastructure\Storage\PhotoLocalStorage;

class Config
{
    /** @var Storage */
    private $storage;
    /** @var Repository */
    private $repository;
    /** @var  Presenter */
    private $presenter;

    /**
     * Config constructor.
     * @param Storage $storage
     * @param Repository $repository
     * @param Presenter $presenter
     */
    public function __construct(Storage $storage, Repository $repository, Presenter $presenter)
    {
        $this->storage = $storage;
        $this->repository = $repository;
        $this->presenter = $presenter;
    }

    public static function getInstanceByArray($config)
    {
        $storage = new Storage($config['storage']['driver'], $config['storage']['config']);
        $repository = new Repository($config['repository']['driver'], $config['repository']['config']);
        $presenter = new Presenter();
        return new self($storage, $repository, $presenter);
    }

    public function getPhotoRepository()
    {
        return new SqlitePhotoRepository(SqliteConfig::getInstanceByArray($this->repository->config()));
    }

    public function getPhotoPresenter()
    {
        return new ArrayPhotoPresenter();
    }
    
    public function getPhotoStorage()
    {
        return new PhotoLocalStorage(LocalStorageConfig::getInstanceByArray($this->storage->config()));
    }
}
