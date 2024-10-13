<?php

declare(strict_types=1);

namespace app\common\grid;

use app\modules\contract\models\Contract;
use Exception;
use kartik\select2\Select2;
use Throwable;
use yii\db\ActiveRecord;
use yii\grid\DataColumn;
use yii\helpers\ArrayHelper;

class ContractColumn extends DataColumn
{
    /**
     * @var int|array|null
     */
    public null|int|array $status = null;

    /**
     * @var string
     */
    public $attribute = 'contractId';

    /**
     * @var string
     */
    public string $relation = 'contractRelation';


    /**
     * @throws Exception
     */
    public function getDataCellValue($model, $key, $index): ?string
    {
        /** @var ActiveRecord $model */

        if ($this->value === null) {
            if ($relation = ArrayHelper::getValue($model, [$this->relation])) {
                $value = ArrayHelper::getValue($relation, ['fullName']);
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
        $query = Contract::find()->hidden();
        if (!empty($this->status)) {
            $query->status($this->status);
        }
        $this->filter = Select2::widget([
            'model' => $this->grid->filterModel,
            'attribute' => $this->attribute,
            'data' => $query->asDropDown(),
            'options' => $options,
        ]);

        return parent::renderFilterCellContent();
    }

}
