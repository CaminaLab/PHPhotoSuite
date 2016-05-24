<?php

namespace PHPhotoSuit\PhotoSuite\Application\Service;

use PHPhotoSuit\PhotoSuite\Domain\ResourceId;

class ThumbFinderRequest
{
    /** @var  ResourceId */
    private $resourceId;
    /** @var ThumbRequestCollection */
    private $thumbRequestCollection;

    /**
     * ThumbFinderRequest constructor.
     * @param ResourceId $resourceId
     * @param ThumbRequestCollection $thumbRequestCollection
     */
    public function __construct(ResourceId $resourceId, ThumbRequestCollection $thumbRequestCollection)
    {
        $this->resourceId = $resourceId;
        $this->thumbRequestCollection = $thumbRequestCollection;
    }

    /**
     * @return ResourceId
     */
    public function resourceId()
    {
        return $this->resourceId;
    }

    /**
     * @return ThumbRequestCollection
     */
    public function thumbRequestCollection()
    {
        return $this->thumbRequestCollection;
    }
}
