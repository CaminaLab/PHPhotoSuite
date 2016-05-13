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

    /**
     * Photo constructor.
     * @param ResourceId $resourceId
     * @param PhotoName $name
     * @param PhotoFormat $format
     */
    public function __construct(ResourceId $resourceId, PhotoName $name, PhotoFormat $format)
    {
        $this->resourceId = $resourceId;
        $this->name = $name;
        $this->format = $format;
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
    public function slug()
    {
        return $this->name->slug();
    }

    /**
     * @return string
     */
    public function format()
    {
        return $this->format->value();
    }
}
