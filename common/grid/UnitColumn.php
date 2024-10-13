<?php

declare(strict_types=1);

namespace app\common\grid;

use app\modules\unit\models\Unit;
use Closure;
use Exception;
use kartik\select2\Select2;
use Throwable;
use yii\db\ActiveRecord;
use yii\grid\DataColumn;
use yii\helpers\ArrayHelper;

class UnitColumn extends DataColumn
{
    /**
     * @var bool
     */
    public bool $showParent = false;

    /**
     * @var bool
     */
    public bool $multiple = true;

    /**
     * @var string
     */
    public $attribute = 'unitId';

    /**
     * @var string
     */
    public string $relation = 'unitRelation';

    /**
     * @throws Exception
     */
    public function getDataCellValue($model, $key, $index): ?string
    {
        /** @var ActiveRecord $model */

        if ($this->value instanceof Closure) {
            return call_user_func($this->value, $model, $key, $index, $this);
        }
        if ($this->value === null) {
            $units = $this->getUnits();
            $attribute = $this->attribute;

            if ($model->hasProperty($this->attribute)) {
                return $model->{$attribute} ? $units[$model->{$attribute}] : '';
            } else {
                if ($relation = ArrayHelper::getValue($model, [$this->relation])) {
                    return $units[$relation->id];
                }
                return '';
            }
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
                'data' => Unit::asDropDown(['showParent' => $this->showParent]),
                'options' => $options,
                'pluginOptions' => [
                    'allowClear' => true,
                ]
            ]);

            return parent::renderFilterCellContent();
        }

        return '';
    }

    /**
     * @return array
     * @throws Exception
     */
    protected function getUnits(): array
    {
        static $units = [];

        if (empty($units)) {
            $units = Unit::asDropDown(['showParent' => $this->showParent]);
        }

        return $units;
    }
}
