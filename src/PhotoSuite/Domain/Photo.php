<?php

namespace PHPhotoSuit\PhotoSuite\Domain;

class Photo
{
    /** @var ResourceId */
    private $resourceId;
    /** @var PhotoName */
    private $name;
    /** @var HttpUrl */
    private $photoHttpUrl;
    /** @var PhotoFile|null */
    private $photoFile;

    /**
     * Photo constructor.
     * @param ResourceId $resourceId
     * @param PhotoName $name
     * @param HttpUrl $photoHttpUrl
     * @param PhotoFile $photoFile
     */
    public function __construct(
        ResourceId $resourceId,
        PhotoName $name,
        HttpUrl $photoHttpUrl,
        PhotoFile $photoFile = null
    ) {
        $this->resourceId = $resourceId;
        $this->name = $name;
        $this->photoHttpUrl = $photoHttpUrl;
        $this->photoFile = $photoFile;
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
    public function getPhotoHttpUrl()
    {
        return $this->photoHttpUrl->value();
    }

    /**
     * @return PhotoFile | null
     */
    public function photoFile()
    {
        return $this->photoFile;
    }
}
