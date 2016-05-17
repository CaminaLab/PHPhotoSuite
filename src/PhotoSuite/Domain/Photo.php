<?php

namespace PHPhotoSuit\PhotoSuite\Domain;

class Photo
{
    /** @var PhotoId */
    private $photoId;
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
     * @param PhotoId $photoId
     * @param ResourceId $resourceId
     * @param PhotoName $name
     * @param HttpUrl $photoHttpUrl
     * @param PhotoFile $photoFile
     */
    public function __construct(
        PhotoId $photoId,
        ResourceId $resourceId,
        PhotoName $name,
        HttpUrl $photoHttpUrl,
        PhotoFile $photoFile = null
    ) {
        $this->photoId = $photoId;
        $this->resourceId = $resourceId;
        $this->name = $name;
        $this->photoHttpUrl = $photoHttpUrl;
        $this->photoFile = $photoFile;
    }

    /**
     * @return string
     */
    public function id()
    {
        return $this->photoId->id();
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

    /**
     * @param PhotoFile $photoFile
     * @return void
     */
    public function updatePhotoFile(PhotoFile $photoFile = null)
    {
        $this->photoFile = $photoFile;
    }
}
