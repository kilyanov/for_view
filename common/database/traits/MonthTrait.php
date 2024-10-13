<?php

declare(strict_types=1);

namespace app\common\database\traits;

use Exception;
use yii\helpers\ArrayHelper;

trait MonthTrait
{
    /**
     * @return string
     */
    protected static function getStatusAttribute(): string
    {
        return 'month';
    }

    /**
     * @return string[]
     */
    public static function getMonthList(): array
    {
        return [
            1 => 'Январь',
            2 => 'Февраль',
            3 => 'Март',
            4 => 'Апрель',
            5 => 'Май',
            6 => 'Июнь',
            7 => 'Июль',
            8 => 'Август',
            9 => 'Сентябрь',
            10 => 'Октябрь',
            11 => 'Ноябрь',
            12 => 'Декабрь',
        ];
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getMonth(): string
    {
        return ArrayHelper::getValue(static::getMonthList(), $this->{static::getStatusAttribute()});
    }
}
