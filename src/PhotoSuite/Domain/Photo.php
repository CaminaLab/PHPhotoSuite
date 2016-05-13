<?php

namespace PHPhotoSuit\PhotoSuite\Domain;

class Photo
{
    /** @var ResourceId */
    private $resourceId;
    /** @var PhotoName */
    private $name;
    /** @var PhotoFormat */
    private $format;
    /** @var HttpUrl */
    private $baseHttpUrl;

    /**
     * Photo constructor.
     * @param ResourceId $resourceId
     * @param PhotoName $name
     * @param PhotoFormat $format
     * @param HttpUrl $httpUrl
     */
    public function __construct(ResourceId $resourceId, PhotoName $name, PhotoFormat $format, HttpUrl $httpUrl)
    {
        $this->resourceId = $resourceId;
        $this->name = $name;
        $this->format = $format;
        $this->baseHttpUrl = $httpUrl;
    }

    /**
     * @return string
     */
    public function resourceId()
    {
        return $this->resourceId->id();
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name->name();
    }

    /**
     * @return string
     */
    public function getPhotoHttpUrl()
    {
        return $this->baseHttpUrl->value() .
                '/' . $this->resourceId->id() .
                '/' . $this->name->name() .
                '.' . $this->format->value();
    }

}
