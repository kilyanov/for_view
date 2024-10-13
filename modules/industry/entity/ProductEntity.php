<?php

declare(strict_types=1);

namespace app\modules\industry\entity;

class ProductEntity extends BaseEntity
{
    /**
     * @param string $name
     * @return string
     */
    public static function getName(string $name): string
    {
        return $name;
    }
}
