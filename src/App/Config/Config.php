<?php

namespace PHPhotoSuit\App\Config;

use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoPresenter;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoRepository;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoStorage;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoThumbGenerator;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoThumbPresenter;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoThumbRepository;
use PHPhotoSuit\PhotoSuite\Infrastructure\Persistence\SqliteConfig;
use PHPhotoSuit\PhotoSuite\Infrastructure\Persistence\SqlitePhotoRepository;
use PHPhotoSuit\PhotoSuite\Infrastructure\Persistence\SqlitePhotoThumbRepository;
use PHPhotoSuit\PhotoSuite\Infrastructure\Presenter\ArrayPhotoPresenter;
use PHPhotoSuit\PhotoSuite\Infrastructure\Presenter\ArrayThumbPresenter;
use PHPhotoSuit\PhotoSuite\Infrastructure\Storage\LocalStorageConfig;
use PHPhotoSuit\PhotoSuite\Infrastructure\Storage\PhotoLocalStorage;
use PHPhotoSuit\PhotoSuite\Infrastructure\ThumbGenerator\ImaginePhotoThumbGenerator;
use PHPhotoSuit\PhotoSuite\Infrastructure\ThumbGenerator\ThumbGeneratorConfig;

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

    /**
     * @return PhotoRepository
     */
    public function getPhotoRepository()
    {
        return new SqlitePhotoRepository(SqliteConfig::getInstanceByArray($this->repository->config()));
    }

    /**
     * @return PhotoPresenter
     */
    public function getPhotoPresenter()
    {
        return new ArrayPhotoPresenter();
    }

    /**
     * @return PhotoStorage
     */
    public function getPhotoStorage()
    {
        return new PhotoLocalStorage(LocalStorageConfig::getInstanceByArray($this->storage->config()));
    }

    /**
     * @return PhotoThumbRepository
     */
    public function getPhotoThumbRepository()
    {
        return new SqlitePhotoThumbRepository(SqliteConfig::getInstanceByArray($this->repository->config()));
    }

    /**
     * @return PhotoThumbGenerator
     */
    public function getPhotoThumbGenerator()
    {
        return new ImaginePhotoThumbGenerator(new ThumbGeneratorConfig());
    }

    /**
     * @return PhotoThumbPresenter
     */
    public function PhotoThumbPresenter()
    {
        return new ArrayThumbPresenter($this->getPhotoPresenter());
    }
}
