<?php

declare(strict_types=1);

namespace app\common\interface;

interface StatusAttributeInterface
{
    public const STATUS_NOT_ACTIVE = 0;
    public const STATUS_ACTIVE = 1;

    /**
     * @return array
     */
    public static function getStatusList(): array;

    /**
     * @return null|string
     */
    public function getStatus(): ?string;
}
