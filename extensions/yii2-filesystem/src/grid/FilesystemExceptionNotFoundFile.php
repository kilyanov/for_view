<?php

declare(strict_types=1);

namespace kilyanov\filesystem\grid;

use League\Flysystem\FilesystemException;
use Throwable;

class FilesystemExceptionNotFoundFile implements FilesystemException
{
    /**
     * @param string|null $path
     */
    public function __construct(
        public ?string $path = null
    )
    {
    }

    /**
     * @return void
     */
    public function getCode()
    {
        // TODO: Implement getCode() method.
    }

    /**
     * @return string
     */
    public function getFile(): string
    {
        // TODO: Implement getFile() method.
    }

    /**
     * @return int
     */
    public function getLine(): int
    {
        // TODO: Implement getLine() method.
    }

    /**
     * @return array
     */
    public function getTrace(): array
    {
        // TODO: Implement getTrace() method.
    }

    /**
     * @return string
     */
    public function getTraceAsString(): string
    {
        // TODO: Implement getTraceAsString() method.
    }

    /**
     * @return Throwable|null
     */
    public function getPrevious(): ?Throwable
    {
        // TODO: Implement getPrevious() method.
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getMessage();
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->path . ' not found';
    }
}