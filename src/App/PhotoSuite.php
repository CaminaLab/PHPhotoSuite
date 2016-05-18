<?php

namespace PHPhotoSuit\App;

use PHPhotoSuit\App\Config\Config;
use PHPhotoSuit\PhotoSuite\Application\Service\Finder;
use PHPhotoSuit\PhotoSuite\Application\Service\PersistHandler;
use PHPhotoSuit\PhotoSuite\Application\Service\SavePhotoRequest;
use PHPhotoSuit\PhotoSuite\Domain\ResourceId;

class PhotoSuite
{
    /** @var PhotoSuite[] */
    private static $instances;

    /** @var Config */
    private $config;

    /** @var Finder */
    private $finder;

    /** @var PersistHandler */
    private $persistHandler;

    private function __construct(Config $config)
    {
        $this->config = $config;
    }

    public static final function create(Config $config)
    {
        $hash = md5(serialize($config));
        if (!isset(self::$instances[$hash])) {
            return self::$instances[$hash] = new self($config);
        }
        return self::$instances[$hash];
    }

    /**
     * @param string $resource
     * @return mixed
     */
    public function findPhotoOf($resource)
    {
        return $this->getFinderInstance()->findPhotoOf(new ResourceId($resource));
    }

    /**
     * @param string $resource
     * @return mixed
     */
    public function findPhotoCollectionOf($resource)
    {
        return $this->getFinderInstance()->findPhotoCollectionOf(new ResourceId($resource));
    }

    /**
     * @param SavePhotoRequest $request
     */
    public function savePhoto(SavePhotoRequest $request)
    {
        $this->getPersistHandlerInstance()->save($request);
    }

    /**
     * @param string $id
     */
    public function deletePhoto($id)
    {
        $this->getPersistHandlerInstance()->delete($id);
    }

    /**
     * @return Finder
     */
    private function getFinderInstance()
    {
        if (is_null($this->finder)) {
            $this->finder = new Finder($this->config->getPhotoRepository(), $this->config->getPhotoPresenter());
        }
        return $this->finder;
    }

    /**
     * @return PersistHandler
     */
    private function getPersistHandlerInstance()
    {
        if (is_null($this->persistHandler)) {
            $this->persistHandler = new PersistHandler(
                $this->config->getPhotoRepository(), 
                $this->config->getPhotoStorage()
            );
        }
        return $this->persistHandler;
    }
}
