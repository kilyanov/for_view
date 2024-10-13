<?php

declare(strict_types=1);

namespace kilyanov\architect\interfaces;

use kilyanov\architect\entity\ElementEntity;

interface BaseFactoryInterface
{
    /**
     * @return array|ElementEntity
     */
    public static function create(): array|ElementEntity;
}
