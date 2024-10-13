<?php

declare(strict_types=1);

namespace kilyanov\filesystem\bus;

use yii\base\InvalidArgumentException;

class FilesystemUpdateStreamCommand
{
    /**
     * @param string $path
     * @param $resource
     * @param array $config
     */
    public function __construct(
        protected string $path,
        protected $resource,
        protected array $config = []
    )
    {
        if (!is_resource($resource)) {
            throw new InvalidArgumentException('Variable does not contain a stream resource');
        }
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return resource
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }
}
