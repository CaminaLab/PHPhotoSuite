<?php

namespace PHPhotoSuit\PhotoSuite\Infrastructure\Persistence;

use PHPhotoSuit\PhotoSuite\Domain\Exception\CollectionNotFoundException;
use PHPhotoSuit\PhotoSuite\Domain\Exception\PhotoNotFoundException;
use PHPhotoSuit\PhotoSuite\Domain\HttpUrl;
use PHPhotoSuit\PhotoSuite\Domain\Lang;
use PHPhotoSuit\PhotoSuite\Domain\Model\Photo;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoAlt;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoAltCollection;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoCollection;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoFile;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoId;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoName;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoRepository;
use PHPhotoSuit\PhotoSuite\Domain\ResourceId;

class MysqlPhotoRepository implements PhotoRepository
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
CREATE TABLE `photo` (
	`id` CHAR(8) NOT NULL,
	`resourceId` VARCHAR(100) NOT NULL,
	`name` VARCHAR(255) NOT NULL,
	`httpUrl` VARCHAR(255) NOT NULL,
	`filePath` VARCHAR(255) NULL DEFAULT NULL,
	PRIMARY KEY (`id`),
	INDEX `photo_resource_id_idx` (`resourceId`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;
SQL;
        $this->pdo->query($createPhoto);

        $createAlternativeText =<<<SQL
CREATE TABLE `alternative_text` (
	`photo_id` CHAR(8) NOT NULL,
	`alt` VARCHAR(255) NOT NULL,
	`lang` CHAR(2) NOT NULL,
	INDEX `alternative_text_photo_id_idx` (`photo_id`),
	CONSTRAINT `alternative_text_photo_FK` FOREIGN KEY (`photo_id`) REFERENCES `photo` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;
SQL;

        $this->pdo->query($createAlternativeText);
    }

    /**
     * @param ResourceId $resourceId
     * @return Photo
     * @throws PhotoNotFoundException
     */
    public function findOneBy(ResourceId $resourceId)
    {
        $sentence  = $this->pdo->prepare("SELECT * FROM `photo` WHERE `resourceId`=:resourceId LIMIT 1");
        $sentence->bindValue(':resourceId', $resourceId->id());
        $sentence->execute();
        $row = $sentence->fetch(\PDO::FETCH_ASSOC);
        if ($row) {
            return $this->createPhotoByRow($row);
        }
        throw new PhotoNotFoundException(sprintf('Photo with resource id %s not found', $resourceId->id()));
    }

    /**
     * @param PhotoId $photoId
     * @return mixed
     * @throws PhotoNotFoundException
     */
    public function findById(PhotoId $photoId)
    {
        $sentence  = $this->pdo->prepare("SELECT * FROM `photo` WHERE `id`=:id LIMIT 1");
        $sentence->bindValue(':id', $photoId->id(), \PDO::PARAM_STR);
        $sentence->execute();
        $row = $sentence->fetch(\PDO::FETCH_ASSOC);
        if ($row) {
            return $this->createPhotoByRow($row);
        }
        throw new PhotoNotFoundException(sprintf('Photo with id %s not found', $photoId->id()));
    }

    /**
     * @param ResourceId $resourceId
     * @return PhotoCollection;
     * @throws CollectionNotFoundException
     */
    public function findCollectionBy(ResourceId $resourceId)
    {
        $sentence  = $this->pdo->prepare("SELECT * FROM `photo` WHERE `resourceId`=:resourceId");
        $sentence->bindValue(':resourceId', $resourceId->id(), \PDO::PARAM_STR);
        $sentence->execute();
        $rows = $sentence->fetchAll(\PDO::FETCH_ASSOC);
        if ($rows) {
            $photos = new PhotoCollection();
            foreach ($rows as $row) {
                $photos[] = $this->createPhotoByRow($row);
            }
            return $photos;
        }
        throw new CollectionNotFoundException(sprintf('Photos with resource id %s not found', $resourceId->id()));
    }

    /**
     * @param Photo $photo
     * @return void
     */
    public function save(Photo $photo)
    {
        $sentence = $this->pdo->prepare(
            "INSERT INTO `photo`(`id`, `resourceId`, `name`, `httpUrl`, `filePath`) " .
            "VALUES(:id, :resourceId, :name, :httpUrl, :filePath)"
        );
        $sentence->bindValue(':id', $photo->id());
        $sentence->bindValue(':resourceId', $photo->resourceId());
        $sentence->bindValue(':name', $photo->name());
        $sentence->bindValue(':httpUrl', $photo->getPhotoHttpUrl());
        $filePath = is_null($photo->photoFile()) ? null : $photo->photoFile()->filePath();
        $filePathType = is_null($photo->photoFile()) ? \PDO::PARAM_NULL : \PDO::PARAM_STR;
        $sentence->bindValue(':filePath', $filePath, $filePathType);
        $sentence->execute();

        foreach ($photo->altCollection() as $photoAlt) {
            $this->saveAlternativeText(new PhotoId($photo->id()), $photoAlt);
        }
    }

    /**
     * @param Photo $photo
     * @return void
     */
    public function delete(Photo $photo)
    {
        $sentence = $this->pdo->prepare("DELETE FROM `photo` WHERE `id`=:id");
        $sentence->bindValue(':id', $photo->id());
        $sentence->execute();
    }

    /**
     * @param PhotoId $photoId
     * @return PhotoId
     */
    public function ensureUniquePhotoId(PhotoId $photoId = null)
    {
        $photoId = is_null($photoId) ? new PhotoId() : $photoId;
        $sentence  = $this->pdo->prepare("SELECT id FROM `photo` WHERE `id`=:id");
        $sentence->bindValue(':id', $photoId->id());
        $sentence->execute();
        $row = $sentence->fetch(\PDO::FETCH_ASSOC);
        if ($row) {
            return $this->ensureUniquePhotoId();
        }
        return $photoId;
    }

    /**
     * @param $row
     * @return Photo
     */
    private function createPhotoByRow($row)
    {
        $photoFile = !empty($row['filePath']) ? new PhotoFile($row['filePath']) : null;
        $photoId = new PhotoId($row['id']);
        return new Photo(
            $photoId,
            new ResourceId($row['resourceId']),
            new PhotoName($row['name']),
            new HttpUrl($row['httpUrl']),
            $this->getAltCollectionBy($photoId),
            $photoFile
        );
    }

    /**
     * @param PhotoId $photoId
     * @return array|PhotoAltCollection
     */
    private function getAltCollectionBy(PhotoId $photoId)
    {
        $sentence  = $this->pdo->prepare("SELECT * FROM `alternative_text` WHERE `photo_id`=:id");
        $sentence->bindValue(':id', $photoId->id());
        $sentence->execute();
        $photoAltCollection = new PhotoAltCollection();
        $rows = $sentence->fetchAll(\PDO::FETCH_ASSOC);
        if ($rows) {
            foreach ($rows as $row) {
                $photoAltCollection[] = new PhotoAlt($photoId, $row['alt'], new Lang($row['lang']));
            }
        }
        return $photoAltCollection;
    }

    /**
     * @param PhotoId $photoId
     * @param PhotoAlt $alt
     */
    private function saveAlternativeText(PhotoId $photoId, PhotoAlt $alt)
    {
        $sentence = $this->pdo->prepare(
            "INSERT INTO `alternative_text`(`photo_id`, `alt`, `lang`) " .
            "VALUES(:photo_id, :alt, :lang)"
        );
        $sentence->bindValue(':photo_id', $photoId->id());
        $sentence->bindValue(':alt', $alt->name());
        $sentence->bindValue(':lang', $alt->lang());
        $sentence->execute();
    }
}
