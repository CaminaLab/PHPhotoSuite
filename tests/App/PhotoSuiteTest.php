<?php

namespace PHPhotoSuit\Tests\App;

use PHPhotoSuit\App\Config\Config;
use PHPhotoSuit\App\PhotoSuite;
use PHPhotoSuit\PhotoSuite\Application\Service\SavePhotoRequest;

class PhotoSuiteTest extends \PHPUnit_Framework_TestCase
{
    /** @var  PhotoSuite */
    private $photoSuite;
    /** @var Config */
    private $config;
    /** @var array */
    private $configAr = [
        'repository' => [
            'driver' => 'sqlite',
            'config' => [
                'dbpath' => __DIR__ . '/storage_and_db/test.db'
            ]
        ],
        'storage' =>[
            'driver' => 'local',
            'config' => [
                'storagePath' => __DIR__ . '/storage_and_db',
                'baseUrl' => 'http://test'
            ]
        ]
    ];

    public function setUp()
    {
        $this->config = Config::getInstanceByArray($this->configAr);
        file_put_contents($this->configAr['repository']['config']['dbpath'], '');
        $this->config->getPhotoRepository()->initialize();
        $this->config->getPhotoThumbRepository()->initialize();
        $this->photoSuite = PhotoSuite::create($this->config);
    }

    /**
     * @test
     */
    public function photoSuitFunctionalTest()
    {
        $this->assertSame($this->photoSuite, PhotoSuite::create($this->config));
        $request = new SavePhotoRequest('test', 'name', __DIR__ . '/photo_to_upload.png', 'alt', 'ES');
        $this->photoSuite->savePhoto($request);
        $this->photoSuite->saveUniquePhoto($request);
        $photo = $this->photoSuite->findPhotoOf('test');
        $this->assertFileExists($photo['file']);
        $photoWhitThumb = $this->photoSuite->findPhotoThumbsOf('test', [['height' => 1, 'width' => 1]]);
        $this->assertFileExists($photoWhitThumb['thumbs']['1x1']['file']);
        $this->assertCount(1, $this->photoSuite->findPhotoCollectionOf('test'));
        $this->assertCount(1, $this->photoSuite->findPhotoCollectionWithItsThumbsOf(
            'test', [['height' => 1, 'width' => 1]]
        ));
        $this->photoSuite->deletePhoto($photo['id']);
        $this->assertFileNotExists($photoWhitThumb['thumbs']['1x1']['file']);
        $this->assertFileNotExists($photo['file']);
    }
    
    public function tearDown()
    {
        unlink($this->configAr['repository']['config']['dbpath']);
    }
}
