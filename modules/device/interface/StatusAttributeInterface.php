<?php

declare(strict_types=1);

namespace app\modules\device\interface;

interface StatusAttributeInterface
{
    public const STATUS_BLOCK = 'block';
    public const STATUS_ACTIVE = 'active';

    /**
     * @return array
     */
    public static function getStatusList(): array;

    /**
     * @return string|null
     */
    public function getStatus(): ?string;
}
