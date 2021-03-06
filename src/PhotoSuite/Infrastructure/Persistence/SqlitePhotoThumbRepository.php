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
        $this->pdo = SqlitePDORegistry::getInstance($this->sqliteConfig->dbPath());
    }

    /**
     * This method should be called once to create the schema of persistence system
     * @return void
     */
    public function initialize()
    {
        $createPhotoTable =<<<SQL
CREATE TABLE IF NOT EXISTS "photo_thumb" (
    "uuid" TEXT NOT NULL PRIMARY KEY,
    "photo_uuid" TEXT NOT NULL,
    "httpUrl" TEXT NOT NULL,
    "height" INTEGER NOT NULL,
    "width" INTEGER NOT NULL,
    "filePath" TEXT,
    FOREIGN KEY(photo_uuid) REFERENCES Photo(uuid)
)
SQL;
        $this->pdo->query($createPhotoTable);
        $this->pdo->query("CREATE INDEX fk_thumb_photo_uuid ON \"photo_thumb\" (photo_uuid);");
    }

    /**
     * @param PhotoId $photoId
     * @param PhotoThumbSize $thumbSize
     * @return PhotoThumb | null
     */
    public function findOneBy(PhotoId $photoId, PhotoThumbSize $thumbSize)
    {
        $sql = <<<SQL
SELECT * FROM "photo_thumb" WHERE photo_uuid=:photo_uuid AND height=:height AND width=:width
SQL;

        $sentence  = $this->pdo->prepare($sql);
        $sentence->bindValue(':photo_uuid',$photoId->id(), \PDO::PARAM_STR);
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
            "INSERT INTO photo_thumb(\"uuid\",\"photo_uuid\",\"httpUrl\",\"height\",\"width\",\"filePath\") " .
            "VALUES(:uuid, :photo_uuid, :httpUrl, :height, :width, :filePath)"
        );
        $sentence->bindValue(':uuid', $thumb->id(), \PDO::PARAM_STR);
        $sentence->bindValue(':photo_uuid', $thumb->photoId(), \PDO::PARAM_STR);
        $sentence->bindValue(':httpUrl', $thumb->photoThumbHttpUrl(), \PDO::PARAM_STR);
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
        $sentence = $this->pdo->prepare("DELETE FROM photo_thumb WHERE uuid=:uuid LIMIT 1");
        $sentence->bindValue(':uuid', $thumb->id());
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
            $photoFile
        );
    }

    /**
     * @param ThumbId $thumbId
     * @return ThumbId
     */
    public function ensureUniqueThumbId(ThumbId $thumbId = null)
    {
        $thumbId = is_null($thumbId) ? new ThumbId() : $thumbId;
        $sentence  = $this->pdo->prepare("SELECT uuid FROM \"photo_thumb\" WHERE uuid=:uuid LIMIT 1");
        $sentence->bindValue(':uuid', $thumbId->id());
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
        $sentence = $this->pdo->query("SELECT * FROM photo_thumb WHERE photo_uuid=\"" . $photoId->id()."\"");
        $rows = $sentence->fetchAll(\PDO::FETCH_ASSOC);
        $thumbCollection = new PhotoThumbCollection();
        if ($rows) {
            foreach ($rows as $row) {
                $thumbCollection[] = $this->createPhotoThumbByRow($row);
            }
        }
        return $thumbCollection;
    }
}
