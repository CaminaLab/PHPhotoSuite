<?php

namespace PHPhotoSuit\PhotoSuite\Infrastructure\Persistence;

use PHPhotoSuit\PhotoSuite\Domain\HttpUrl;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoFile;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoId;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoThumb;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoThumbMode;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoThumbRepository;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoThumbSize;
use PHPhotoSuit\PhotoSuite\Domain\Model\ThumbId;

class SqlitePhotoThumbRepository implements PhotoThumbRepository
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
     * This method should be called once to create the schema of persistence system
     * @return void
     */
    public function initialize()
    {
        $createPhotoTable =<<<SQL
CREATE TABLE IF NOT EXISTS "PhotoThumb" (
    "uuid" TEXT NOT NULL PRIMARY KEY,
    "photo_uuid" TEXT NOT NULL,
    "httpUrl" TEXT NOT NULL,
    "height" INTEGER NOT NULL,
    "width" INTEGER NOT NULL,
    "mode" TEXT NOT NULL,
    "filePath" TEXT,
    FOREIGN KEY(photo_uuid) REFERENCES Photo(uuid)
)
SQL;
        $this->pdo->query($createPhotoTable);
        $this->pdo->query("CREATE INDEX fk_thumb_photo_uuid ON \"PhotoThumb\" (photo_uuid);");
    }

    /**
     * @param PhotoId $photoId
     * @param PhotoThumbSize $thumbSize
     * @param PhotoThumbMode $thumbMode
     * @return PhotoThumb | null
     */
    public function findOneBy(PhotoId $photoId, PhotoThumbSize $thumbSize, PhotoThumbMode $thumbMode)
    {
        $sql = <<<SQL
SELECT * FROM "PhotoThumb" WHERE photo_uuid=:photo_uuid AND height=:height AND width=:width AND mode=:mode
SQL;

        $sentence  = $this->pdo->prepare($sql);
        $sentence->bindParam(':photo_uuid',$photoId->id(), \PDO::PARAM_STR);
        $sentence->bindParam(':height', $thumbSize->height(), \PDO::PARAM_INT);
        $sentence->bindParam(':width', $thumbSize->width(), \PDO::PARAM_INT);
        $sentence->bindParam(':mode', $thumbMode->value(), \PDO::PARAM_STR);
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
            "INSERT INTO PhotoThumb(\"uuid\",\"photo_uuid\",\"httpUrl\",\"height\",\"width\",\"mode\",\"filePath\") " .
            "VALUES(:uuid, :photo_uuid, :httpUrl, :height, :width, :mode, :filePath)"
        );
        $sentence->bindParam(':uuid', $thumb->id(), \PDO::PARAM_STR);
        $sentence->bindParam(':photo_uuid', $thumb->photoId(), \PDO::PARAM_STR);
        $sentence->bindParam(':httpUrl', $thumb->photoThumbHttpUrl(), \PDO::PARAM_STR);
        $sentence->bindParam(':height', $thumb->height(), \PDO::PARAM_INT);
        $sentence->bindParam(':width', $thumb->width(), \PDO::PARAM_INT);
        $sentence->bindParam(':mode', $thumb->mode(), \PDO::PARAM_STR);
        $filePath = is_null($thumb->photoThumbFile()) ? null : $thumb->photoThumbFile()->filePath();
        $filePathType = is_null($thumb->photoThumbFile()) ? \PDO::PARAM_NULL : \PDO::PARAM_STR;
        $sentence->bindParam(':filePath', $filePath, $filePathType);
        $sentence->execute();
    }

    private function createPhotoThumbByRow($row)
    {
        $photoFile = !empty($row['filePath']) ? new PhotoFile($row['filePath']) : null;
        return new PhotoThumb(
            new ThumbId($row['uuid']),
            new PhotoId($row['photo_uuid']),
            new HttpUrl($row['httpUrl']),
            new PhotoThumbSize($row['height'], $row['width']),
            new PhotoThumbMode($row['mode']),
            $photoFile
        );
    }
}
