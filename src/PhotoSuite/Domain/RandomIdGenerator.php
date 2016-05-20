<?php

namespace PHPhotoSuit\PhotoSuite\Domain;

class RandomIdGenerator
{
    /**
     * @var string
     */
    private static $base62Chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

    /**
     * @param $long
     * @return string
     */
    public static function getBase62($long)
    {
        return self::getBase($long, 62);
    }

    /**
     * @param $id
     * @param $long
     * @return bool
     */
    public static function isValidBase62($id, $long)
    {
        return strlen($id) === $long && preg_match('/[0-9A-Za-z]+/', $id);
    }

    /**
     * @param $long
     * @return string
     */
    public static function getBase36($long)
    {
        return self::getBase($long, 36);
    }

    /**
     * @param $id
     * @param $long
     * @return bool
     */
    public static function isValidBase36($id, $long)
    {
        return strlen($id) === $long && preg_match('/[0-9A-Z]+/', $id);
    }

    /**
     * @param $long
     * @param $base
     * @return string
     */
    private static function getBase($long, $base)
    {
        $id = '';
        for ($i = 0; $i < $long; $i++) {
            $id .= self::$base62Chars[rand(0, $base - 1)];
        }
        return $id;
    }
}
