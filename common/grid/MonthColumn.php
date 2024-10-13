<?php

declare(strict_types=1);

namespace app\common\grid;

use Exception;
use yii\db\ActiveRecord;
use yii\grid\DataColumn;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class MonthColumn extends DataColumn
{
    /**
     * @var string
     */
    public $attribute = 'month';
    /**
     * @var string|null
     */
    public ?string $url = null;

    /**
     * @throws Exception
     */
    public function getDataCellValue($model, $key, $index): ?string
    {
        /** @var ActiveRecord $model */

        if ($this->value === null) {
            $value = ArrayHelper::getValue($model, $this->attribute);
            $result = ($value === null) ? '' : $this->getListMonth()[$value];
            return $this->url !== null ?
                Html::a($result, [$this->url . $model->id], ['data-pjax' => 0]) : $result;
        }

        return parent::getDataCellValue($model, $key, $index);
    }

    /**
     * @return string
     */
    protected function renderFilterCellContent(): string
    {
        $this->filter = $this->getListMonth();

        return parent::renderFilterCellContent();
    }

    /**
     * @return string[]
     */
    private function getListMonth(): array
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
}
