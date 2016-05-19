<?php

namespace PHPhotoSuit\PhotoSuite\Domain;

use PHPhotoSuit\PhotoSuite\Domain\Exception\FileNotFoundException;

class File
{
    /** @var string */
    private $filePath;

    private $mimeTypes = [

        'text/plain' => 'txt',
        'text/html' => 'html',
        'text/css' => 'css',
        'application/javascript' => 'js',
        'application/json' => 'json',
        'application/xml' => 'xml',
        'application/x-shockwave-flash' => 'swf',
        'video/x-flv' => 'flv',

        // images
        'image/png' => 'png',
        'image/jpeg' => 'jpeg',
        'image/gif' => 'gif',
        'image/bmp' => 'bmp',
        'image/vnd.microsoft.icon' => 'ico',
        'image/tiff' => 'tiff',
        'image/svg+xml' => 'svg',

        // archives
        'application/zip' => 'zip',
        'application/x-rar-compressed' => 'rar',
        'application/x-msdownload' => 'exe',
        'application/vnd.ms-cab-compressed' => 'cab',

        // audio/video
        'audio/mpeg' => 'mp3',
        'video/quicktime' => 'qt',

        // adobe
        'application/pdf' => 'pdf',
        'image/vnd.adobe.photoshop' => 'psd',
        'application/postscript' => 'ps',

        // ms office
        'application/msword' => 'doc',
        'application/rtf' => 'rtf',
        'application/vnd.ms-excel' => 'xls',
        'application/vnd.ms-powerpoint' => 'ppt',

        // open office
        'application/vnd.oasis.opendocument.text' => 'odt',
        'application/vnd.oasis.opendocument.spreadsheet' => 'ods',
    ];



    /**
     * @param string $filePath
     * @throws FileNotFoundException
     */
    public function __construct($filePath)
    {
        if (!file_exists($filePath)) {
            throw new FileNotFoundException(sprintf('File %s not found.', $filePath));
        }
        $this->filePath = $filePath;
    }

    /**
     * @return string
     */
    public function filePath()
    {
        return $this->filePath;
    }

    /**
     * @return string
     */
    public function format()
    {
        $resource = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($resource, $this->filePath);
        finfo_close($resource);
        return isset($this->mimeTypes[$mimeType]) ? $this->mimeTypes[$mimeType]: null;
    }
}
