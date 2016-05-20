<?php

namespace PHPhotoSuit\PhotoSuite\Domain\Model;

use Cocur\Slugify\Slugify;
use PHPhotoSuit\PhotoSuite\Domain\Exception\InvalidLengthException;

class PhotoName
{
    /** @var  string */
    private $name;
    /** @var  string */
    private $slug;

    /**
     * @param string $name
     * @throws InvalidLengthException
     */
    public function __construct($name)
    {
        if (strlen($name) > 255) {
            throw new InvalidLengthException();
        }
        $this->name = $name;
        $this->slug = (new Slugify())->slugify($name);
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
}
