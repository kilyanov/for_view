<?php

declare(strict_types=1);

namespace app\common\grid;

use app\modules\industry\models\OrderList;
use Closure;
use kartik\select2\Select2;
use Throwable;
use yii\grid\DataColumn;

class OrderTypeColumn extends DataColumn
{
    public $attribute = 'type';

    public function getDataCellValue($model, $key, $index): ?string
    {
        if ($this->value instanceof Closure) {
            return call_user_func($this->value, $model, $key, $index, $this);
        }

        return $model->getType();
    }

    /**
     * @return string
     * @throws Throwable
     */
    protected function renderFilterCellContent(): string
    {
        if (empty($this->filter)) {
            /** @var OrderList $filterClass */
            $filterClass = $this->grid->filterModel::class;
            $options = array_merge(['prompt' => ''], $this->filterInputOptions);
            $this->filter = Select2::widget([
                'model' => $this->grid->filterModel,
                'attribute' => $this->attribute,
                'data' => $filterClass::getTypeList(),
                'options' => $options,
            ]);
        }
        else {
            $filter = $this->filter;
            $options = array_merge(['prompt' => ''], $this->filterInputOptions);
            $this->filter = Select2::widget([
                'model' => $this->grid->filterModel,
                'attribute' => $this->attribute,
                'data' => $filter,
                'options' => $options,
            ]);
        }
        return parent::renderFilterCellContent();
    }
}
