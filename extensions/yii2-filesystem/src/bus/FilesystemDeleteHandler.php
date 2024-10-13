<?php

declare(strict_types=1);

namespace kilyanov\filesystem\bus;

use League\Flysystem\FilesystemException;
use League\Flysystem\FilesystemOperator;

class FilesystemDeleteHandler
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
     * @param FilesystemDeleteCommand $command
     * @return void
     * @throws FilesystemException
     */
    public function __invoke(FilesystemDeleteCommand $command): void
    {
        $path = $command->getPath();

        $this->filesystem->delete($path);
    }
}
