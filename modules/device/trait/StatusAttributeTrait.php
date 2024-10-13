<?php

declare(strict_types=1);

namespace app\modules\device\trait;

use app\modules\device\interface\StatusAttributeInterface;
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
     * @return string[]
     */
    public static function getStatusList(): array
    {
        return [
            StatusAttributeInterface::STATUS_BLOCK => 'Заблокирован',
            StatusAttributeInterface::STATUS_ACTIVE => 'Активный',
        ];
    }

    /**
     * @return string|null
     * @throws Exception
     */
    public function getStatus(): ?string
    {
        return ArrayHelper::getValue(static::getStatusList(), $this->{static::getStatusAttribute()});
    }
}
