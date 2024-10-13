<?php

declare(strict_types=1);

namespace app\common\grid;

use app\modules\device\models\Device;
use Exception;
use kartik\select2\Select2;
use Throwable;
use yii\grid\DataColumn;

class DeviceVerificationPeriodColumn extends DataColumn
{

    /**
     * @var string
     */
    public $attribute = 'verificationPeriod';

    /**
     * @throws Exception
     */
    public function getDataCellValue($model, $key, $index): ?string
    {
        /** @var Device $model */
        if ($this->value === null) {
            $value = $model->getVerificationPeriod();
            return !empty($value) ? $value : '';
        }

        return parent::getDataCellValue($model, $key, $index);
    }

    /**
     * @throws Throwable
     */
    protected function renderFilterCellContent(): string
    {
        $options = array_merge(['prompt' => '', 'multiple' => true], $this->filterInputOptions);
        $this->filter = Select2::widget([
            'model' => $this->grid->filterModel,
            'attribute' => $this->attribute,
            'data' => Device::getVerificationPeriodList(),
            'options' => $options,
        ]);

        return parent::renderFilterCellContent();
    }
}
