<?php

declare(strict_types=1);

namespace kilyanov\repository\bus;

class RepositoryDeleteCommand
{
    /**
     * @param int|string $id
     */
    public function __construct(
        protected int|string $id
    )
    {
    }

    /**
     * @return int|string
     */
    public function getId(): int|string
    {
        return $this->id;
    }
}
