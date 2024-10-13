<?php

declare(strict_types=1);

namespace app\common\database\traits;

use app\common\interface\HiddenAttributeInterface;
use Exception;
use yii\helpers\ArrayHelper;

trait HiddenAttributeTrait
{
    /**
     * @return string
     */
    protected static function getHiddenAttribute(): string
    {
        return 'hidden';
    }

    /**
     * @return bool
     */
    public function isHidden(): bool
    {
        return (bool)$this->{static::getHiddenAttribute()};
    }

    /**
     * @return array
     */
    public static function getHiddenList(): array
    {
        return [
            HiddenAttributeInterface::HIDDEN_NO => 'Нет',
            HiddenAttributeInterface::HIDDEN_YES => 'Да',
        ];
    }

    /**
     * @return null|string
     * @throws Exception
     */
    public function getHidden(): ?string
    {
        return ArrayHelper::getValue(static::getHiddenList(), $this->{static::getHiddenAttribute()});
    }
}
