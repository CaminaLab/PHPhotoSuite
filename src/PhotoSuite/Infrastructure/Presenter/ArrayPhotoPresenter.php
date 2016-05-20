<?php

namespace PHPhotoSuit\PhotoSuite\Infrastructure\Presenter;

use PHPhotoSuit\PhotoSuite\Domain\Model\Photo;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoAlt;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoAltCollection;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoCollection;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoPresenter;

class ArrayPhotoPresenter implements PhotoPresenter
{

    /**
     * @param Photo $photo
     * @return array
     */
    public function write(Photo $photo)
    {
        return [
            'id' => $photo->id(),
            'resourceId' => $photo->resourceId(),
            'name' => $photo->name(),
            'url' => $photo->getPhotoHttpUrl(),
            'alts' => $this->getAlts($photo->altCollection()),
            'file' => is_null($photo->photoFile()) ? '' : $photo->photoFile()->filePath()
        ];
    }

    /**
     * @param PhotoCollection $collection
     * @return array
     */
    public function writeCollection(PhotoCollection $collection)
    {
        return array_map([ArrayPhotoPresenter::class, 'write'], $collection->toArray());
    }

    /**
     * @param PhotoAltCollection $altCollection
     * @return array
     */
    private function getAlts(PhotoAltCollection $altCollection)
    {
        $alts = [];
        /** @var PhotoAlt $alt */
        foreach ($altCollection as $alt) {
            $alts[$alt->lang()] = [
                'name' => $alt->name(),
                'slug' => $alt->slug()
            ];
        }
        return $alts;
    }
}
