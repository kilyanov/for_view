<?php

declare(strict_types=1);

namespace app\common\grid;

use yii\db\ActiveRecord;
use yii\grid\DataColumn;
use yii\helpers\ArrayHelper;

class DeviceRejectColumn extends DataColumn
{
    /**
     * @var string
     */
    public $attribute = 'reject';

    /**
     * @var string
     */
    public string $relation = 'deviceRejectionRelation';

    /**
     * @throws \Exception
     */
    public function getDataCellValue($model, $key, $index): ?string
    {
        /** @var ActiveRecord $model */
        if ($this->value === null) {
            if ($relation = ArrayHelper::getValue($model, [$this->relation])) {
                return ArrayHelper::getValue($relation, ['rejection_date'], '');
            }

            return '';
        }

        return parent::getDataCellValue($model, $key, $index);
    }
}
