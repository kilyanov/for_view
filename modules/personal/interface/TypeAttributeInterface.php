<?php

declare(strict_types=1);

namespace app\modules\personal\interface;

interface TypeAttributeInterface
{
    public const TYPE_JOB = 0;
    public const TYPE_ITR = 1;

    /**
     * @return array
     */
    public static function getTypeList(): array;

    /**
     * @return null|string
     */
    public function getType(): ?string;
}
