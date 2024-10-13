<?php

declare(strict_types=1);

namespace app\common\grid;

use app\modules\device\models\Device;
use app\modules\impact\models\Impact;
use Exception;
use kartik\select2\Select2;
use Throwable;
use yii\grid\DataColumn;
use yii\helpers\ArrayHelper;

class DeviceToImpactColumn extends DataColumn
{
    /**
     * @var string
     */
    public $attribute = 'impactId';

    /**
     * @var string
     */
    public string $relation = 'deviceToImpactRelation.impactRelation';

    /**
     * @throws Exception
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
            'data' => Impact::asDropDown(),
            'options' => $options,
        ]);

        return parent::renderFilterCellContent();
    }
}
