<?php

declare(strict_types=1);

namespace kilyanov\architect\entity;

class RowEntity extends GroupEntity
{
    /**
     * @var string|null
     */
    protected ?string $template = 'row';
}
