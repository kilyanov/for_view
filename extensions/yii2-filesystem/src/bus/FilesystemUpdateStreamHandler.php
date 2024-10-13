<?php

declare(strict_types=1);

namespace kilyanov\filesystem\bus;

use League\Flysystem\FilesystemException;
use League\Flysystem\FilesystemOperator;

class FilesystemUpdateStreamHandler
{
    /**
     * @param FilesystemOperator $filesystem
     */
    public function __construct(
        protected FilesystemOperator $filesystem
    )
    {
    }

    /**
     * @param FilesystemUpdateStreamCommand $command
     * @return void
     * @throws FilesystemException
     */
    public function __invoke(FilesystemUpdateStreamCommand $command): void
    {
        $path = $command->getPath();
        $resource = $command->getResource();
        $config = $command->getConfig();

        $this->filesystem->writeStream($path, $resource, $config);
    }
}
