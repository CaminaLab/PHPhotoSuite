<?php

namespace PHPhotoSuit\PhotoSuite\Infrastructure\Persistence;

use PHPhotoSuit\PhotoSuite\Domain\HttpUrl;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoFile;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoId;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoThumb;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoThumbCollection;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoThumbRepository;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoThumbSize;
use PHPhotoSuit\PhotoSuite\Domain\Model\ThumbId;

class MysqlPhotoThumbRepository implements PhotoThumbRepository
{

    /** @var MysqlConfig */
    private $config;
    /** @var \PDO */
    private $pdo;

    /**
     * MysqlPhotoRepository constructor.
     * @param MysqlConfig $config
     */
    public function __construct(MysqlConfig $config)
    {
        $this->config = $config;
        $this->pdo = MysqlPDORegistry::getInstance($this->config);
    }

    /**
     * This method should be called once to create the schema of persistence system
     * @return void
     */
    public function initialize()
    {
        $createPhoto =<<<SQL
CREATE TABLE `photo_thumb` (
	`id` CHAR(8) NOT NULL,
	`photo_id` CHAR(8) NOT NULL,
	`httpUrl` VARCHAR(255) NOT NULL,
	`height` SMALLINT(6) NOT NULL,
	`width` SMALLINT(6) NOT NULL,
	`filePath` VARCHAR(255) NULL DEFAULT NULL,
	PRIMARY KEY (`id`),
	INDEX `photo_thumb_photo_idx` (`photo_id`),
	CONSTRAINT `photo_thumb_photo_FK` FOREIGN KEY (`photo_id`) REFERENCES `photo` (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;
SQL;
        $this->pdo->query($createPhoto);
    }

    /**
     * @param PhotoId $photoId
     * @param PhotoThumbSize $thumbSize
     * @return PhotoThumb | null
     */
    public function findOneBy(PhotoId $photoId, PhotoThumbSize $thumbSize)
    {
        $sql = <<<SQL
SELECT * FROM `photo_thumb` WHERE photo_id=:photo_id AND `height`=:height AND `width`=:width
SQL;

        $sentence  = $this->pdo->prepare($sql);
        $sentence->bindValue(':photo_id',$photoId->id(), \PDO::PARAM_STR);
        $sentence->bindValue(':height', $thumbSize->height(), \PDO::PARAM_INT);
        $sentence->bindValue(':width', $thumbSize->width(), \PDO::PARAM_INT);
        $sentence->execute();
        $row = $sentence->fetch(\PDO::FETCH_ASSOC);

        if ($row) {
            return $this->createPhotoThumbByRow($row);
        }
    }

    /**
     * @param PhotoThumb $thumb
     * @return void
     */
    public function save(PhotoThumb $thumb)
    {
        $sentence = $this->pdo->prepare(
            "INSERT INTO `photo_thumb`(`id`,`photo_id`,`httpUrl`,`height`,`width`, `filePath`) " .
            "VALUES(:id, :photo_id, :httpUrl, :height, :width, :filePath)"
        );
        $sentence->bindValue(':id', $thumb->id());
        $sentence->bindValue(':photo_id', $thumb->photoId());
        $sentence->bindValue(':httpUrl', $thumb->photoThumbHttpUrl());
        $sentence->bindValue(':height', $thumb->height(), \PDO::PARAM_INT);
        $sentence->bindValue(':width', $thumb->width(), \PDO::PARAM_INT);
        $filePath = is_null($thumb->photoThumbFile()) ? null : $thumb->photoThumbFile()->filePath();
        $filePathType = is_null($thumb->photoThumbFile()) ? \PDO::PARAM_NULL : \PDO::PARAM_STR;
        $sentence->bindValue(':filePath', $filePath, $filePathType);
        $sentence->execute();
    }

    /**
     * @param PhotoThumb $thumb
     * @return void
     */
    public function delete(PhotoThumb $thumb)
    {
        $sentence = $this->pdo->prepare("DELETE FROM `photo_thumb` WHERE `id`=:id");
        $sentence->bindValue(':id', $thumb->id());
        $sentence->execute();
    }

    /**
     * @param ThumbId $thumbId
     * @return ThumbId
     */
    public function ensureUniqueThumbId(ThumbId $thumbId = null)
    {
        $thumbId = is_null($thumbId) ? new ThumbId() : $thumbId;
        $sentence  = $this->pdo->prepare("SELECT id FROM `photo_thumb` WHERE `id`=:id");
        $sentence->bindValue(':id', $thumbId->id());
        $sentence->execute();
        $row = $sentence->fetch(\PDO::FETCH_ASSOC);
        if ($row) {
            return $this->ensureUniqueThumbId();
        }
        return $thumbId;
    }

    /**
     * @param PhotoId $photoId
     * @return PhotoThumbCollection
     */
    public function findCollectionBy(PhotoId $photoId)
    {
        $sentence = $this->pdo->prepare("SELECT * FROM `photo_thumb` WHERE `photo_id`=:photo_id");
        $sentence->bindValue(':photo_id', $photoId->id());
        $sentence->execute();
        $rows = $sentence->fetchAll(\PDO::FETCH_ASSOC);
        $thumbCollection = new PhotoThumbCollection();
        if ($rows) {
            foreach ($rows as $row) {
                $thumbCollection[] = $this->createPhotoThumbByRow($row);
            }
        }
        return $thumbCollection;
    }

    /**
     * @param $row
     * @return PhotoThumb
     */
    private function createPhotoThumbByRow($row)
    {
        $photoFile = !empty($row['filePath']) ? new PhotoFile($row['filePath']) : null;
        return new PhotoThumb(
            new ThumbId($row['id']),
            new PhotoId($row['photo_id']),
            new HttpUrl($row['httpUrl']),
            new PhotoThumbSize($row['height'], $row['width']),
            $photoFile
        );
    }
}
