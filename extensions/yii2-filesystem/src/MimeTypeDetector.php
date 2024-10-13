<?php

declare(strict_types=1);

namespace kilyanov\filesystem;

use League\MimeTypeDetection\ExtensionToMimeTypeMap;
use League\MimeTypeDetection\FinfoMimeTypeDetector;

class MimeTypeDetector extends FinfoMimeTypeDetector
{
    private const MIME_TYPE = [
        'application/x-empty',
        'text/plain',
        'text/x-asm',
        'application/octet-stream',
        'inode/x-empty',
        'text/html',
    ];

    /**
     * @param string $magicFile
     * @param ExtensionToMimeTypeMap|null $extensionMap
     * @param int|null $bufferSampleSize
     * @param array $inconclusiveMimetypes
     */
    public function __construct(
        string                 $magicFile = '',
        ExtensionToMimeTypeMap $extensionMap = null,
        ?int                   $bufferSampleSize = null,
        array                  $inconclusiveMimetypes = self::MIME_TYPE
    )
    {
        parent::__construct($magicFile, $extensionMap, $bufferSampleSize, $inconclusiveMimetypes);
    }
}