<?php

namespace PHPhotoSuit\Tests\PhotoSuite\Infrastructure\Persistence;

use PHPhotoSuit\PhotoSuite\Domain\Exception\CollectionNotFoundException;
use PHPhotoSuit\PhotoSuite\Domain\Exception\PhotoNotFoundException;
use PHPhotoSuit\PhotoSuite\Domain\HttpUrl;
use PHPhotoSuit\PhotoSuite\Domain\Model\Photo;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoCollection;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoId;
use PHPhotoSuit\PhotoSuite\Domain\Model\PhotoName;
use PHPhotoSuit\PhotoSuite\Domain\ResourceId;
use PHPhotoSuit\PhotoSuite\Infrastructure\Persistence\SqliteConfig;
use PHPhotoSuit\PhotoSuite\Infrastructure\Persistence\SqlitePhotoRepository;

class SqlitePhotoRepositoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var string */
    private $dbPath = __DIR__ . '/database/test.db';
    /** @var SqlitePhotoRepository */
    private $repository;
    /** @var Photo */
    private $photo;

    public function setUp()
    {
        file_put_contents($this->dbPath, '');
        $sqliteConfig = new SqliteConfig($this->dbPath);
        $this->repository = new SqlitePhotoRepository($sqliteConfig);
        $this->repository->initialize();
        $this->photo = new Photo(
            new PhotoId(),
            new ResourceId('test'),
            new PhotoName('test'),
            new HttpUrl('http://test')
        );
        $this->repository->save($this->photo);
    }


    /**
     * @test
     */
    public function nonExistentDatabaseThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        new SqliteConfig('');
    }

    /**
     * @test
     */
    public function findOneByResourceIdWorks()
    {
        $this->assertEquals($this->photo, $this->repository->findOneBy(new ResourceId($this->photo->resourceId())));
    }

    /**
     * @test
     */
    public function findByIdWorks()
    {
        $this->assertEquals($this->photo, $this->repository->findById(new PhotoId($this->photo->id())));
    }

    /**
     * @test
     */
    public function findCollectionByWorks()
    {
        $this->assertEquals(
            new PhotoCollection([$this->photo]),
            $this->repository->findCollectionBy(new ResourceId($this->photo->resourceId()))
        );
    }

    /**
     * @test
     * @dataProvider kindOfExceptionsAndCallingMethods
     */
    public function exceptions($exception, $method, $params)
    {
        $this->repository->delete($this->photo);
        $this->expectException($exception);
        call_user_func_array([$this->repository, $method], $params);
    }

    public function kindOfExceptionsAndCallingMethods()
    {
        return [
            [PhotoNotFoundException::class, 'findOneBy', [new ResourceId('test')]],
            [PhotoNotFoundException::class, 'findById', [new PhotoId()]],
            [CollectionNotFoundException::class, 'findCollectionBy', [new ResourceId('test')]]
        ];
    }

    public function tearDown()
    {
        unlink($this->dbPath);
    }
}
