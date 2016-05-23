<?php

namespace PHPhotoSuit\PhotoSuite\Domain\Model;

use Cocur\Slugify\Slugify;
use PHPhotoSuit\PhotoSuite\Domain\Exception\InvalidLengthException;
use PHPhotoSuit\PhotoSuite\Domain\Lang;

class PhotoAlt
{
    /** @var PhotoId */
    private $photoId;
    /** @var  string */
    private $name;
    /** @var  string */
    private $slug;
    /** @var Lang */
    private $lang;

    /**
     * @param PhotoId $photoId
     * @param string $name
     * @param Lang $lang
     * @throws InvalidLengthException
     */
    public function __construct(PhotoId $photoId, $name, Lang $lang)
    {
        if (strlen($name) > 255) {
            throw new InvalidLengthException();
        }
        $this->photoId = $photoId;
        $this->name = $name;
        $this->slug = (new Slugify())->slugify($name);
        $this->lang = $lang;
    }

    /**
     * @return string
     */
    public function photoId()
    {
        return $this->photoId->id();
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function slug()
    {
        return $this->slug;
    }

    /**
     * @return string
     */
    public function lang()
    {
        return $this->lang->value();
    }


}
