<?php

declare(strict_types=1);

namespace app\common\grid;

use app\modules\product\models\Product;
use kartik\select2\Select2;
use Throwable;
use yii\db\ActiveRecord;
use yii\grid\DataColumn;
use yii\helpers\ArrayHelper;

class ProductColumn extends DataColumn
{
    /**
     * @var bool
     */
    public bool $multiple = true;

    /**
     * @var string
     */
    public $attribute = 'productId';

    /**
     * @var string
     */
    public string $relation = 'productRelation';

    /**
     * @throws \Exception
     */
    public function getDataCellValue($model, $key, $index): ?string
    {
        /** @var ActiveRecord $model */

        if ($this->value instanceof \Closure) {
            return call_user_func($this->value, $model, $key, $index, $this);
        }
        if ($relation = ArrayHelper::getValue($model, [$this->relation])) {
            $value = ArrayHelper::getValue($relation, ['fullName']);
            return !empty($value) ? $value : '';
        }

        return parent::getDataCellValue($model, $key, $index);
    }

    /**
     * @return string
     * @throws Throwable
     */
    protected function renderFilterCellContent(): string
    {
        if ($this->filter !== false) {
            $options = array_merge(
                [
                    'prompt' => '',
                    'multiple' => $this->multiple
                ],
                $this->filterInputOptions
            );
            $this->filter = Select2::widget([
                'model' => $this->grid->filterModel,
                'attribute' => $this->attribute,
                'data' => Product::asDropDown(),
                'options' => $options,
                'pluginOptions' => [
                    'allowClear' => true,
                ]
            ]);

            return parent::renderFilterCellContent();
        }

        return '';
    }
}
