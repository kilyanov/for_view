<?php

declare(strict_types=1);

namespace app\common\database\traits;

use app\common\interface\StatusAttributeInterface;
use Exception;
use yii\helpers\ArrayHelper;

trait StatusAttributeTrait
{
    /**
     * @return string
     */
    protected static function getStatusAttribute(): string
    {
        return 'status';
    }

    /**
     * @return array
     */
    public static function getStatusList(): array
    {
        return [
            StatusAttributeInterface::STATUS_NOT_ACTIVE => 'Не активный',
            StatusAttributeInterface::STATUS_ACTIVE => 'Активный',
        ];
    }

    /**
     * @return null|string
     * @throws Exception
     */
    public function getStatus(): ?string
    {
        return ArrayHelper::getValue(static::getStatusList(), $this->{static::getStatusAttribute()});
    }
}
