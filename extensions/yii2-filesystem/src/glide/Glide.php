<?php

declare(strict_types=1);

namespace kilyanov\filesystem\glide;

use League\Glide\Filesystem\FileNotFoundException;
use League\Glide\Filesystem\FilesystemException;
use League\Glide\Server;

class Glide implements GlideInterface
{
    /**
     * Glide constructor.
     *
     * @param Server $server
     * @param string $sourcePath
     * @param string $cacheUrl
     */
    public function __construct(
        protected Server $server,
        protected string $sourcePath = '/',
        protected string $cacheUrl = '/'
    )
    {
    }

    /**
     * @param string $path
     * @param array $params
     *
     * @return string|null
     */
    public function make(string $path, array $params = []): ?string
    {
        $path = $this->getSourcePath() . '/' . $path;

        try {
            return $this->getCacheUrl() . '/' . $this->server->makeImage($path, $params);
        } catch (FileNotFoundException|FilesystemException $exception) {
            return null;
        }
    }

    /**
     * @return string
     */
    public function getSourcePath(): string
    {
        return $this->sourcePath;
    }

    /**
     * @param string $sourcePath
     */
    public function setSourcePath(string $sourcePath): void
    {
        $this->sourcePath = rtrim($sourcePath, '/');
    }

    /**
     * @return string
     */
    public function getCacheUrl(): string
    {
        return $this->cacheUrl;
    }

    /**
     * @param string $cacheUrl
     */
    public function setCacheUrl(string $cacheUrl): void
    {
        $this->cacheUrl = rtrim($cacheUrl, '/');
    }
}