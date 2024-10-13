<?php

declare(strict_types=1);

namespace app\common\grid;

use Exception;
use yii\db\ActiveRecord;
use yii\grid\DataColumn;
use yii\helpers\ArrayHelper;

class DeviceVerificationColumn extends DataColumn
{
    /**
     * @var string
     */
    public $attribute = 'verification';

    /**
     * @var string
     */
    public string $relation = 'deviceVerificationRelation';

    /**
     * @throws Exception
     */
    public function getDataCellValue($model, $key, $index): ?string
    {
        /** @var ActiveRecord $model */
        if ($this->value === null) {
            if ($relation = ArrayHelper::getValue($model, [$this->relation])) {

                return ArrayHelper::getValue($relation, ['verification_date'], '');
            }

            return '';
        }

        return parent::getDataCellValue($model, $key, $index);
    }
}
