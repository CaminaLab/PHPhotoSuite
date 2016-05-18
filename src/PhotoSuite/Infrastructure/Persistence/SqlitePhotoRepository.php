<?php

namespace PHPhotoSuit\PhotoSuite\Infrastructure\Persistence;

use PHPhotoSuit\PhotoSuite\Domain\Exception\CollectionNotFoundException;
use PHPhotoSuit\PhotoSuite\Domain\Exception\PhotoNotFoundException;
use PHPhotoSuit\PhotoSuite\Domain\HttpUrl;
use PHPhotoSuit\PhotoSuite\Domain\Photo;
use PHPhotoSuit\PhotoSuite\Domain\PhotoCollection;
use PHPhotoSuit\PhotoSuite\Domain\PhotoFile;
use PHPhotoSuit\PhotoSuite\Domain\PhotoId;
use PHPhotoSuit\PhotoSuite\Domain\PhotoName;
use PHPhotoSuit\PhotoSuite\Domain\PhotoRepository;
use PHPhotoSuit\PhotoSuite\Domain\ResourceId;

class SqlitePhotoRepository implements PhotoRepository
{
    /** @var SqliteConfig */
    private $sqliteConfig;
    /** @var \PDO */
    private $pdo;

    /**
     * @param SqliteConfig $sqliteConfig
     */
    public function __construct(SqliteConfig $sqliteConfig)
    {
        $this->sqliteConfig = $sqliteConfig;
        $this->pdo = new \PDO('sqlite:' . $this->sqliteConfig->dbPath());
    }

    /**
     * @inheritdoc
     */
    public function initialize()
    {
        $createTable =<<<SQL
CREATE TABLE IF NOT EXISTS "Photo" (
    "uuid" TEXT NOT NULL,
    "resourceId" TEXT NOT NULL,
    "name" TEXT NOT NULL,
    "httpUrl" TEXT NOT NULL,
    "filePath" TEXT
)
SQL;

        $this->pdo->query($createTable);
        $this->pdo->query("CREATE UNIQUE INDEX PK ON \"Photo\" (uuid);");
        $this->pdo->query("CREATE INDEX resource ON \"Photo\" (resourceId);");
    }

    /**
     * @param ResourceId $resourceId
     * @return Photo
     * @throws PhotoNotFoundException
     */
    public function findOneBy(ResourceId $resourceId)
    {
        $sentence  = $this->pdo->prepare("SELECT * FROM \"Photo\" WHERE resourceId=:resourceId LIMIT 1");
        $sentence->bindParam(':resourceId', $resourceId->id(), \PDO::PARAM_STR);
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
        $sentence  = $this->pdo->prepare("SELECT * FROM \"Photo\" WHERE uuid=:uuid LIMIT 1");
        $sentence->bindParam(':uuid', $photoId->id());
        $sentence->execute();
        $row = $sentence->fetch(\PDO::FETCH_ASSOC);
        if ($row) {
            return $this->createPhotoByRow($row);
        }
        throw new PhotoNotFoundException(sprintf('Photo with uuid %s not found', $photoId->id()));
    }

    /**
     * @param ResourceId $resourceId
     * @return PhotoCollection;
     * @throws CollectionNotFoundException
     */
    public function findCollectionBy(ResourceId $resourceId)
    {
        $sentence  = $this->pdo->prepare("SELECT * FROM \"Photo\" WHERE resourceId=:resourceId");
        $sentence->bindParam(':resourceId', $resourceId->id(), \PDO::PARAM_STR);
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
            "INSERT INTO Photo(\"uuid\", \"resourceId\", \"name\", \"httpUrl\", \"filePath\") " .
            "VALUES(:uuid, :resourceId, :name, :httpUrl, :filePath)"
        );
        $sentence->bindParam(':uuid', $photo->id());
        $sentence->bindParam(':resourceId', $photo->resourceId());
        $sentence->bindParam(':name', $photo->name());
        $sentence->bindParam(':httpUrl', $photo->getPhotoHttpUrl());
        $filePath = is_null($photo->photoFile()) ? null : $photo->photoFile()->filePath();
        $filePathType = is_null($photo->photoFile()) ? \PDO::PARAM_NULL : \PDO::PARAM_STR;
        $sentence->bindParam(':filePath', $filePath, $filePathType);
        $sentence->execute();
    }

    /**
     * @param Photo $photo
     * @return void
     */
    public function delete(Photo $photo)
    {
        $sentence = $this->pdo->prepare(
            "DELETE FROM Photo WHERE uuid=:uuid LIMIT 1"
        );
        $sentence->bindParam(':uuid', $photo->id());
        $sentence->execute();
    }

    /**
     * @param $row
     * @return Photo
     */
    public function createPhotoByRow($row)
    {
        $photoFile = !empty($row['filePath']) ? new PhotoFile($row['filePath']) : null;
        return new Photo(
            new PhotoId($row['uuid']),
            new ResourceId($row['resourceId']),
            new PhotoName($row['name']),
            new HttpUrl($row['httpUrl']),
            $photoFile
        );
    }
}
