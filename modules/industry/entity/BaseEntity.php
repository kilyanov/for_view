<?php

declare(strict_types=1);

namespace app\modules\industry\entity;


abstract class BaseEntity
{
    /**
     * @param string $name
     * @return string
     */
    abstract static function getName(string $name): string;
}
