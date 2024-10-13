<?php

declare(strict_types=1);

namespace kilyanov\filesystem\glide;

interface GlideInterface
{
    /**
     * @return string
     */
    public function getSourcePath(): string;

    /**
     * @param string $sourcePath
     */
    public function setSourcePath(string $sourcePath): void;

    /**
     * @return string
     */
    public function getCacheUrl(): string;

    /**
     * @param string $cacheUrl
     */
    public function setCacheUrl(string $cacheUrl): void;

    /**
     * @param string $path
     * @param array $params
     *
     * @return string|null
     */
    public function make(string $path, array $params = []): ?string;
}