<?php

declare(strict_types=1);

namespace kilyanov\filesystem\bus;

class FilesystemDeleteCommand
{
    /**
     * @param string $path
     */
    public function __construct(
        protected string $path
    )
    {
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }
}
