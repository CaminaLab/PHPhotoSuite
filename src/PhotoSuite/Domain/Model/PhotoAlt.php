<?php

namespace PHPhotoSuit\PhotoSuite\Domain\Model;

use Cocur\Slugify\Slugify;
use PHPhotoSuit\PhotoSuite\Domain\Exception\InvalidLengthException;
use PHPhotoSuit\PhotoSuite\Domain\Lang;

class PhotoAlt
{
    /** @var  string */
    private $name;
    /** @var  string */
    private $slug;
    /** @var Lang */
    private $lang;

    /**
     * @param string $name
     * @param Lang $lang
     * @throws InvalidLengthException
     */
    public function __construct($name, Lang $lang)
    {
        if (strlen($name) > 255) {
            throw new InvalidLengthException();
        }
        $this->name = $name;
        $this->slug = (new Slugify())->slugify($name);
        $this->lang = $lang;
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
