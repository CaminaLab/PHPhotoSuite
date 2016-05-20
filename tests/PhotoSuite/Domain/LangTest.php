<?php

namespace PHPhotoSuit\Tests\PhotoSuite\Domain;

use PHPhotoSuit\PhotoSuite\Domain\Exception\InvalidLanguageException;
use PHPhotoSuit\PhotoSuite\Domain\Lang;

class LangTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider languages
     * @param string $language
     */
    public function validLanguage($language)
    {
        $lang = new Lang($language);
        $this->assertSame($language, $lang->value());
    }

    public function languages() {
        return [
            [Lang::LANGUAGE_EN],
            [Lang::LANGUAGE_ES],
        ];
    }

    /**
     * @test
     */
    public function exception()
    {
        $this->expectException(InvalidLanguageException::class);
        new Lang('invalid');
    }
}
