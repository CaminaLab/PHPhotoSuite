<?php

namespace PHPhotoSuit\PhotoSuite\Domain\Model;

use PHPhotoSuit\PhotoSuite\Domain\HttpUrl;

class PhotoThumb
{
    /** @var ThumbId */
    private $thumbId;
    /** @var PhotoId */
    private $photoId;
    /** @var HttpUrl */
    private $photoThumbHttpUrl;
    /** @var PhotoThumbSize */
    private $photoThumbSize;
    /** @var PhotoThumbMode */
    private $mode;
    /** @var PhotoFile|null */
    private $photoThumbFile;

    /**
     * @param ThumbId $thumbId
     * @param PhotoId $photoId
     * @param HttpUrl $photoThumbHttpUrl
     * @param PhotoThumbSize $photoThumbSize
     * @param PhotoThumbMode $mode
     * @param PhotoFile $photoThumbFile
     */
    public function __construct(
        ThumbId $thumbId,
        PhotoId $photoId, 
        HttpUrl $photoThumbHttpUrl, 
        PhotoThumbSize $photoThumbSize,
        PhotoThumbMode $mode,
        PhotoFile $photoThumbFile = null
    ) {
        $this->thumbId = $thumbId;
        $this->photoId = $photoId;
        $this->photoThumbHttpUrl = $photoThumbHttpUrl;
        $this->photoThumbSize = $photoThumbSize;
        $this->mode = $mode;
        $this->photoThumbFile = $photoThumbFile;
    }

    /**
     * @return string
     */
    public function id()
    {
        return $this->thumbId->id();
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
    public function photoThumbHttpUrl()
    {
        return $this->photoThumbHttpUrl->value();
    }

    /**
     * @return int
     */
    public function height()
    {
        return $this->photoThumbSize->height();
    }

    /**
     * @return int
     */
    public function width()
    {
        return $this->photoThumbSize->width();
    }

    /**
     * @return string
     */
    public function mode()
    {
        return $this->mode->value();
    }

    /**
     * @return null|PhotoFile
     */
    public function photoThumbFile()
    {
        return $this->photoThumbFile;
    }
    
    public function updatePhotoThumbFile(PhotoFile $photoThumbFile = null)
    {
        $this->photoThumbFile = $photoThumbFile;
    }
}
