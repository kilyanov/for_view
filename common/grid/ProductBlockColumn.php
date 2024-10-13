<?php

declare(strict_types=1);

namespace app\common\grid;

use app\modules\product\models\ProductBlock;
use Closure;
use Exception;
use kartik\select2\Select2;
use Throwable;
use yii\db\ActiveRecord;
use yii\grid\DataColumn;
use yii\helpers\ArrayHelper;

class ProductBlockColumn extends DataColumn
{
    /**
     * @var bool
     */
    public bool $showFullName = false;

    /**
     * @var bool
     */
    public bool $multiple = true;

    /**
     * @var string
     */
    public $attribute = 'productBlockId';

    /**
     * @var string
     */
    public string $relation = 'productBlockRelation';

    /**
     * @throws Exception
     */
    public function getDataCellValue($model, $key, $index): ?string
    {
        /** @var ActiveRecord $model */

        if ($this->value instanceof Closure) {
            return call_user_func($this->value, $model, $key, $index, $this);
        }
        if ($relation = ArrayHelper::getValue($model, [$this->relation])) {
            if ($this->showFullName) {
                $value = ArrayHelper::getValue($relation, ['fullName']);
            }
            else {
                $value = implode(' ', array_filter([
                    ArrayHelper::getValue($relation, ['name']),
                    ArrayHelper::getValue($relation, ['mark'])
                ]));
            }
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
                'data' => ProductBlock::asDropDown([
                    'productNodeId' => $this->grid->filterModel->productNodeId,
                    'productId' => $this->grid->filterModel->productId
                ]),
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
