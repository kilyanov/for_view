<?php

declare(strict_types=1);

namespace kilyanov\filesystem\bus;

class FilesystemGridBuilderCommand
{
    /**
     * FilesystemGridBuilderCommand constructor.
     *
     * @param string $path
     * @param string|null $name
     * @param string|null $extension
     */
    public function __construct(
        protected string $path,
        protected ?string $name = null,
        protected ?string $extension = null
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

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getExtension(): ?string
    {
        return $this->extension;
    }
}