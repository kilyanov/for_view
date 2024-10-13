<?php

declare(strict_types=1);

namespace app\modules\personal\traits;

use app\modules\personal\interface\TypeAttributeInterface;
use Exception;
use yii\helpers\ArrayHelper;

trait TypeAttributeTrait
{
    /**
     * @return string
     */
    protected static function getTypeAttribute(): string
    {
        return 'type';
    }

    /**
     * @return array
     */
    public static function getTypeList(): array
    {
        return [
            TypeAttributeInterface::TYPE_JOB => 'Раб.',
            TypeAttributeInterface::TYPE_ITR => 'ИТР',
        ];
    }

    /**
     * @return null|string
     * @throws Exception
     */
    public function getType(): ?string
    {
        return ArrayHelper::getValue(static::getTypeList(), $this->{static::getTypeAttribute()});
    }
}
