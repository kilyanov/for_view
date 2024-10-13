<?php

declare(strict_types=1);

namespace app\common\grid;

use app\modules\impact\models\Impact;
use yii\db\ActiveRecord;
use yii\grid\DataColumn;
use yii\helpers\ArrayHelper;

class ImpactColumn extends DataColumn
{
    /**
     * @var string
     */
    public $attribute = 'impactId';

    /**
     * @var string
     */
    public string $relation = 'impactRelation';

    /**
     * @throws \Exception
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
     */
    protected function renderFilterCellContent(): string
    {
        $this->filter = Impact::find()->hidden()->asDropDown();

        return parent::renderFilterCellContent();
    }

}
