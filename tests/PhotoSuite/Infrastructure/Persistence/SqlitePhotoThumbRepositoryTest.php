<?php

namespace PHPhotoSuit\Tests\PhotoSuite\Infrastructure\Persistence;

use PHPhotoSuit\PhotoSuite\Domain\HttpUrl;
use PHPhotoSuit\PhotoSuite\Domain\Model\Photo;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoAltCollection;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoId;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoName;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoThumb;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoThumbMode;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoThumbSize;
use PHPhotoSuit\PhotoSuite\Domain\Model\ThumbId;
use PHPhotoSuit\PhotoSuite\Domain\ResourceId;
use PHPhotoSuit\PhotoSuite\Infrastructure\Persistence\SqliteConfig;
use PHPhotoSuit\PhotoSuite\Infrastructure\Persistence\SqlitePDORegistry;
use PHPhotoSuit\PhotoSuite\Infrastructure\Persistence\SqlitePhotoRepository;
use PHPhotoSuit\PhotoSuite\Infrastructure\Persistence\SqlitePhotoThumbRepository;

class SqlitePhotoThumbRepositoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var string */
    private $dbPath = __DIR__ . '/database/test.db';
    /** @var SqlitePhotoRepository */
    private $photoRepository;
    /** @var SqlitePhotoThumbRepository */
    private $thumbRepository;
    /** @var PhotoId */
    private $photoId;
    /** @var Photo */
    private $photo;
    /** @var ThumbId */
    private $thumbId;
    /** @var PhotoThumb */
    private $thumb;

    public function setUp()
    {
        file_put_contents($this->dbPath, '');
        $sqliteConfig = new SqliteConfig($this->dbPath);
        $this->photoRepository = new SqlitePhotoRepository($sqliteConfig);
        $this->photoRepository->initialize();
        $this->thumbRepository = new SqlitePhotoThumbRepository($sqliteConfig);
        $this->thumbRepository->initialize();
        $httpUrl = new HttpUrl('http://test');
        $this->photoId = new PhotoId();
        $this->photo = new Photo(
            $this->photoId,
            new ResourceId('test'),
            new PhotoName('test'),
            $httpUrl,
            new PhotoAltCollection()
        );
        $this->thumbId = new ThumbId();
        $this->thumb = new PhotoThumb(
            $this->thumbId,
            $this->photoId,
            $httpUrl,
            new PhotoThumbSize(1,1)
        );
        $this->photoRepository->save($this->photo);
        $this->thumbRepository->save($this->thumb);
    }

    /**
     * @test
     */
    public function findOneBy()
    {
        $this->assertEquals(
            $this->thumb,
            $this->thumbRepository->findOneBy(
                $this->photoId,
                new PhotoThumbSize(1,1)
            )
        );
        $this->assertNull($this->thumbRepository->findOneBy(
            new PhotoId(),
            new PhotoThumbSize(1,1)
        ));
    }

    /**
     * @test
     */
    public function ensureUniqueThumbId()
    {
        $this->assertNotEquals($this->thumbRepository->ensureUniqueThumbId($this->thumbId), $this->thumbId);
    }

    public function tearDown()
    {
        SqlitePDORegistry::removeInstance($this->dbPath);
        unlink($this->dbPath);
    }
}
