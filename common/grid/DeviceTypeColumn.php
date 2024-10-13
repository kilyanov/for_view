<?php

declare(strict_types=1);

namespace app\common\grid;

use app\modules\device\models\DeviceType;
use yii\db\ActiveRecord;
use yii\grid\DataColumn;
use yii\helpers\ArrayHelper;

/**
 *
 * @property-read array $deviceTypes
 */
class DeviceTypeColumn extends DataColumn
{

    /**
     * @var string
     */
    public $attribute = 'deviceTypeId';

    /**
     * @var string
     */
    public string $relation = 'deviceTypeRelation';

    /**
     * @throws \Exception
     */
    public function getDataCellValue($model, $key, $index): ?string
    {
        /** @var ActiveRecord $model */
        if ($this->value === null) {
            $deviceTypes = $this->getDeviceTypes();
            $attribute = $this->attribute;

            return $deviceTypes[$model->$attribute] ?? '';
        }

        return parent::getDataCellValue($model, $key, $index);
    }

    /**
     * @return array
     */
    protected function getDeviceTypes(): array
    {
        static $deviceTypes = [];

        if (empty($deviceTypes)) {
            $data = ArrayHelper::map(
                DeviceType::find()->status()->hidden()->asArray()->all(),
                'id',
                static function (array $row) {
                    return $row['name'];
                }
            );

            $deviceTypes = $data;
        }

        return $deviceTypes;
    }
}
