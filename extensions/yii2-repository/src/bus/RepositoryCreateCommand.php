<?php

declare(strict_types=1);

namespace kilyanov\repository\bus;

class RepositoryCreateCommand
{
    /**
     * RepositoryCreateCommand constructor.
     *
     * @param string $title
     * @param string $src
     * @param array $meta
     */
    public function __construct(
        protected string $title,
        protected string $src,
        protected array $meta
    )
    {
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getSrc(): string
    {
        return $this->src;
    }

    /**
     * @return array
     */
    public function getMeta(): array
    {
        return $this->meta;
    }
}
