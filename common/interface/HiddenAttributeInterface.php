<?php

declare(strict_types=1);

namespace app\common\interface;

interface HiddenAttributeInterface
{
    public const HIDDEN_NO = 0;
    public const HIDDEN_YES = 1;

    /**
     * @return bool
     */
    public function isHidden(): bool;

    /**
     * @return array
     */
    public static function getHiddenList(): array;

    /**
     * @return null|string
     */
    public function getHidden(): ?string;
}
