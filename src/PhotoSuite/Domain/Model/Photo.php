<?php

namespace PHPhotoSuit\PhotoSuite\Domain\Model;

use PHPhotoSuit\PhotoSuite\Domain\HttpUrl;
use PHPhotoSuit\PhotoSuite\Domain\Lang;
use PHPhotoSuit\PhotoSuite\Domain\Position;
use PHPhotoSuit\PhotoSuite\Domain\ResourceId;

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
    /** @var PhotoAltCollection */
    private $altCollection;
    /** @var Position */
    private $position;
    /** @var PhotoFile|null */
    private $photoFile;

    /**
     * @param PhotoId $photoId
     * @param ResourceId $resourceId
     * @param PhotoName $name
     * @param HttpUrl $photoHttpUrl
     * @param PhotoAltCollection $photoAltCollection
     * @param Position $position
     * @param PhotoFile $photoFile
     */
    public function __construct(
        PhotoId $photoId,
        ResourceId $resourceId,
        PhotoName $name,
        HttpUrl $photoHttpUrl,
        PhotoAltCollection $photoAltCollection,
        Position $position,
        PhotoFile $photoFile = null
    ) {
        $this->photoId = $photoId;
        $this->resourceId = $resourceId;
        $this->name = $name;
        $this->photoHttpUrl = $photoHttpUrl;
        $this->altCollection = $photoAltCollection;
        $this->position = $position;
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

    /**
     * @param Lang $lang
     * @return PhotoAlt
     */
    public function altByLang(Lang $lang)
    {
        /** @var PhotoAlt $photoAlt */
        foreach ($this->altCollection as $photoAlt) {
            if ($photoAlt->lang() === $lang->value()) {
                return $photoAlt;
            }
        }
    }

    /**
     * @return PhotoAltCollection
     */
    public function altCollection()
    {
        return $this->altCollection;
    }

    /**
     * @return int
     */
    public function position()
    {
        return $this->position->value();
    }
}
