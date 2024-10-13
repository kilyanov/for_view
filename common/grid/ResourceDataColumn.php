<?php

declare(strict_types=1);

namespace app\common\grid;

use Exception;
use yii\db\ActiveRecord;
use yii\grid\DataColumn;
use yii\helpers\ArrayHelper;

class ResourceDataColumn extends DataColumn
{
    /**
     * @var string
     */
    public $attribute = 'resourceId';

    /**
     * @var string
     */
    public string $relation = 'resourceRelation';

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
}
