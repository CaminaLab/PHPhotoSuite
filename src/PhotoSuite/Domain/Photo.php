<?php

namespace PHPhotoSuit\PhotoSuite\Domain;

class Photo
{
    /** @var ResourceId */
    private $resourceId;
    /** @var PhotoName */
    private $name;
    /** @var PhotoFile */
    private $file;
    /** @var HttpUrl */
    private $baseHttpUrl;

    /**
     * Photo constructor.
     * @param ResourceId $resourceId
     * @param PhotoName $name
     * @param PhotoFile $file
     * @param HttpUrl $httpUrl
     */
    public function __construct(ResourceId $resourceId, PhotoName $name, PhotoFile $file, HttpUrl $httpUrl)
    {
        $this->resourceId = $resourceId;
        $this->name = $name;
        $this->file = $file;
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
                '.' . $this->file->format();
    }

}
