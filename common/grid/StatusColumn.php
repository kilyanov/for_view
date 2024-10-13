<?php

declare(strict_types=1);

namespace app\common\grid;

use kartik\select2\Select2;
use Throwable;
use yii\grid\DataColumn;

class StatusColumn extends DataColumn
{
    /**
     * @var string
     */
    public $attribute = 'status';

    /**
     * @var string[]
     */
    public $headerOptions = ['style' => 'width:180px'];

    /**
     * @param $model
     * @param $key
     * @param $index
     * @return string|null
     */
    public function getDataCellValue($model, $key, $index): ?string
    {
        return $model->getStatus();
    }

    /**
     * @return string
     * @throws Throwable
     */
    protected function renderFilterCellContent(): string
    {
        if ($this->filter !== false) {
            $filterClass = $this->grid->filterModel::class;
            $options = array_merge(['prompt' => ''], $this->filterInputOptions);
            $this->filter = Select2::widget([
                'model' => $this->grid->filterModel,
                'attribute' => $this->attribute,
                'data' => $filterClass::getStatusList(),
                'options' => $options,
                'pluginOptions' => [
                    'allowClear' => true,
                ]
            ]);
        }

        return parent::renderFilterCellContent();
    }
}
