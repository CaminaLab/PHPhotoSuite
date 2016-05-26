<?php

namespace PHPhotoSuit\App;

use PHPhotoSuit\App\Config\Config;
use PHPhotoSuit\PhotoSuite\Application\Service\Finder;
use PHPhotoSuit\PhotoSuite\Application\Service\PersistHandler;
use PHPhotoSuit\PhotoSuite\Application\Service\SavePhotoRequest;
use PHPhotoSuit\PhotoSuite\Application\Service\ThumbFinder;
use PHPhotoSuit\PhotoSuite\Application\Service\ThumbFinderRequest;
use PHPhotoSuit\PhotoSuite\Application\Service\ThumbRequest;
use PHPhotoSuit\PhotoSuite\Application\Service\ThumbRequestCollection;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoThumbSize;
use PHPhotoSuit\PhotoSuite\Domain\ResourceId;

class PhotoSuite
{
    /** @var PhotoSuite[] */
    private static $instances;
    
    /** @var Config */
    private $config;
    
    /** @var ThumbFinder */
    private $thumbFinder;
    
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
     * @param $resource
     * @param array $thumbSizes
     * @return mixed
     */
    public function findPhotoThumbsOf($resource, array $thumbSizes)
    {
        $thumbRequestCollection = new ThumbRequestCollection();
        foreach ($thumbSizes as $thumbSize) {
            $thumbRequestCollection[] = new ThumbRequest(
                new PhotoThumbSize($thumbSize['height'], $thumbSize['width'])
            );
        }
        $request = new ThumbFinderRequest(new ResourceId($resource), $thumbRequestCollection);

        return $this->getThumbsFinder()->findPhotoThumbsOf($request);
    }

    /**
     * @param $resource
     * @param array $thumbSizes
     * @return mixed
     */
    public function findPhotoCollectionWithItsThumbsOf($resource, array $thumbSizes)
    {
        $thumbRequestCollection = new ThumbRequestCollection();
        foreach ($thumbSizes as $thumbSize) {
            $thumbRequestCollection[] = new ThumbRequest(
                new PhotoThumbSize($thumbSize['height'], $thumbSize['width'])
            );
        }
        $request = new ThumbFinderRequest(new ResourceId($resource), $thumbRequestCollection);

        return $this->getThumbsFinder()->findPhotoCollectionWithItsThumbsOf($request);
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
                $this->config->getPhotoStorage(),
                $this->config->getPhotoThumbRepository()
            );
        }
        return $this->persistHandler;
    }

    /**
     * @return ThumbFinder
     */
    private function getThumbsFinder()
    {
        if (is_null($this->thumbFinder)) {
            $this->thumbFinder = new ThumbFinder(
                $this->config->getPhotoRepository(),
                $this->config->getPhotoThumbRepository(),
                $this->config->getPhotoThumbGenerator(),
                $this->config->getPhotoStorage(),
                $this->config->PhotoThumbPresenter()
            );
        }
        return $this->thumbFinder;
    }
}
