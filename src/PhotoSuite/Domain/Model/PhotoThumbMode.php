<?php

namespace PHPhotoSuit\PhotoSuite\Domain\Model;

use Imagine\Image\ImageInterface;
use JimmyOak\DataType\Enum;

class PhotoThumbMode extends Enum
{
    const THUMBNAIL_INSET = ImageInterface::THUMBNAIL_INSET;
    const THUMBNAIL_OUTBOUND= ImageInterface::THUMBNAIL_OUTBOUND;
}
