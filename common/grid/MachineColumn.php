<?php

declare(strict_types=1);

namespace app\common\grid;

use app\modules\industry\models\Machine;
use Closure;
use Exception;
use kartik\select2\Select2;
use Throwable;
use yii\db\ActiveRecord;
use yii\grid\DataColumn;
use yii\helpers\ArrayHelper;

class MachineColumn extends DataColumn
{
    /**
     * @var string
     */
    public $attribute = 'machineId';

    /**
     * @var string
     */
    public string $relation = 'machineRelation';

    /**
     * @var string|null|Closure
     */
    public null|string|Closure $productId = null;

    /**
     * @var bool
     */
    public bool $visibleFullName = true;

    /**
     * @throws Exception
     */
    public function getDataCellValue($model, $key, $index): ?string
    {
        /** @var ActiveRecord $model */
        if ($this->value === null) {
            $machine = $this->getMachine();
            if (empty($machine)) return null;
             if ($relation = ArrayHelper::getValue($model, [$this->relation])) {
                    if ($this->visibleFullName) {
                        $value = ArrayHelper::getValue($relation, ['fullName']);
                    }
                    else {
                        $value = ArrayHelper::getValue($relation, ['number']);
                    }
                    return !empty($value) ? $value : '';
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
        $options = array_merge(['prompt' => ''], $this->filterInputOptions);

        if (!is_string($this->productId)) {
            $productId = call_user_func($this->productId, $this->grid->filterModel, $this);
        }
        else {
            $productId = $this->productId;
        }
        if ($productId == null) {
            $this->filter = false;
        }
        else {
            $machines = $this->getMachineList();

            $this->filter = Select2::widget([
                'model' => $this->grid->filterModel,
                'attribute' => $this->attribute,
                'data' => $machines,
                'options' => $options,
            ]);
        }

        return parent::renderFilterCellContent();
    }

    /**
     * @return array
     */
    private function getMachineList(): array
    {
        static $machines = [];
        if (empty($machines)) {
            if (!is_string($this->productId)) {
                $productId = call_user_func($this->productId, $this->grid->filterModel, $this);
            }
            else {
                $productId = $this->productId;
            }
            if ($this->visibleFullName) {
                $machines = Machine::find()->hidden()->product($productId)->order()->asDropDown();
            }
            else {
                $models = Machine::find()->hidden()->product($productId)->order()->asArray()->all();
                $machines = ArrayHelper::map($models, 'id', 'number');
            }
        }

        return $machines;
    }

    /**
     * @return string|null
     */
    private function getMachine(): ?string
    {
        static $machine = false;

        if (!$machine && $machine !== null) {
            if (!is_string($this->productId)) {
                $productId = call_user_func($this->productId, $this->grid->filterModel, $this);
            }
            else {
                $productId = $this->productId;
            }
            $machine = Machine::find()->hidden()->product($productId)->one();
        }

        /** @var $machine Machine */
        return $machine?->id;
    }
}
