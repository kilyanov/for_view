<?php

declare(strict_types=1);

namespace kilyanov\filesystem\grid;

class GridBuilder implements GridBuilderInterface
{
    /**
     * @param int $level
     * @param int $length
     * @param string $algorithm
     */
    public function __construct(
        protected readonly int    $level = 2,
        protected readonly int    $length = 2,
        protected readonly string $algorithm = 'sha1'
    )
    {
    }

    /**
     * @param string $path
     * @param string|null $name
     * @param string|null $extension
     *
     * @return string
     * @throws FilesystemExceptionNotFoundFile
     */
    public function make(string $path, ?string $name = null, ?string $extension = null): string
    {
        if (!is_file($path) || !is_readable($path)) {
            throw new FilesystemExceptionNotFoundFile($path);
        }

        if ($name === null) {
            if ($extension === null) {
                $extension = pathinfo($path, PATHINFO_EXTENSION);
            }

            $name = hash($this->algorithm, microtime()) . '.' . $extension;
        }

        if ($this->level > 0) {
            $grid = [];
            $key = hash_file($this->algorithm, $path);

            for ($i = 0; $i < $this->level; ++$i) {
                $prefix = substr($key, $i + $i, $this->length);
                if (!$prefix) {
                    continue;
                } else {
                    $grid[] = $prefix;
                }
            }

            return implode('/', $grid) . '/' . $name;
        } else {
            return $name;
        }
    }
}