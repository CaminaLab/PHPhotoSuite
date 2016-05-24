<?php

namespace PHPhotoSuit\PhotoSuite\Application\Service;


use JimmyOak\Collection\TypedCollection;

class ThumbRequestCollection extends TypedCollection
{
    public function __construct()
    {
        parent::__construct(ThumbRequest::class);
    }
}
