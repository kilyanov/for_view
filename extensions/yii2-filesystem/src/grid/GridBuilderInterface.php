<?php

declare(strict_types=1);

namespace kilyanov\filesystem\grid;

interface GridBuilderInterface
{
    /**
     * @param string $path
     * @param null|string $name
     * @param null|string $extension
     *
     * @return string
     */
    public function make(string $path, ?string $name = null, ?string $extension = null): string;
}