<?php

declare(strict_types=1);

namespace kilyanov\filesystem\bus;

use League\Flysystem\FilesystemException;
use League\Flysystem\FilesystemOperator;

class FilesystemWriteStreamHandler
{
    /**
     * FilesystemWriteStreamHandler constructor.
     *
     * @param FilesystemOperator $filesystem
     */
    public function __construct(
        protected FilesystemOperator $filesystem
    )
    {
    }

    /**
     * @param FilesystemWriteStreamCommand $command
     * @return void
     * @throws FilesystemException
     */
    public function __invoke(FilesystemWriteStreamCommand $command): void
    {
        $path = $command->getPath();
        $resource = $command->getResource();
        $config = $command->getConfig();

        $this->filesystem->writeStream($path, $resource, $config);
    }
}
