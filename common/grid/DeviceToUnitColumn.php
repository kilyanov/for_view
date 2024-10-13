<?php

declare(strict_types=1);

namespace app\common\grid;

use app\modules\device\models\Device;
use app\modules\unit\models\Unit;
use kartik\select2\Select2;
use Throwable;
use yii\grid\DataColumn;
use yii\helpers\ArrayHelper;

class DeviceToUnitColumn extends DataColumn
{
    /**
     * @var string
     */
    public $attribute = 'unitId';

    /**
     * @var string
     */
    public string $relation = 'deviceToUnitRelation.unitRelation';

    /**
     * @throws \Exception
     */
    public function getDataCellValue($model, $key, $index): ?string
    {
        /** @var Device $model */
        if ($this->value === null) {
            if ($relation = ArrayHelper::getValue($model, [$this->relation])) {
                $value = ArrayHelper::getValue($relation, ['fullName']);

                return !empty($value) ? $value : '';
            }

            return '';
        }

        return parent::getDataCellValue($model, $key, $index);
    }

    /**
     * @return string
     * @throws Throwable
     */
    protected function renderFilterCellContent(): string
    {
        $options = array_merge(['prompt' => '', 'multiple' => true], $this->filterInputOptions);
        $this->filter = Select2::widget([
            'model' => $this->grid->filterModel,
            'attribute' => $this->attribute,
            'data' => Unit::asDropDown(),
            'options' => $options,
        ]);

        return parent::renderFilterCellContent();
    }
}
