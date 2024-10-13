<?php

declare(strict_types=1);

namespace kilyanov\filesystem\bus;

use kilyanov\filesystem\grid\GridBuilderInterface;

class FilesystemGridBuilderHandler
{
    /**
     * FilesystemGridBuilderHandler constructor.
     *
     * @param GridBuilderInterface $gridBuilder
     */
    public function __construct(
        protected GridBuilderInterface $gridBuilder
    )
    {
    }

    /**
     * @param FilesystemGridBuilderCommand $command
     *
     * @return string
     */
    public function __invoke(FilesystemGridBuilderCommand $command): string
    {
        $path = $command->getPath();
        $name = $command->getName();
        $extension = $command->getExtension();

        return $this->gridBuilder->make($path, $name, $extension);
    }
}
