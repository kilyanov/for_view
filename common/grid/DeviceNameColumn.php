<?php

declare(strict_types=1);

namespace app\common\grid;

use app\modules\device\models\DeviceName;
use Yii;
use yii\caching\TagDependency;
use yii\db\ActiveRecord;
use yii\grid\DataColumn;
use yii\helpers\ArrayHelper;

/**
 *
 * @property-read array $deviceNames
 */
class DeviceNameColumn extends DataColumn
{

    /**
     * @var string
     */
    public $attribute = 'deviceNameId';

    /**
     * @var string
     */
    public string $relation = 'deviceNameRelation';

    /**
     * @throws \Exception
     */
    public function getDataCellValue($model, $key, $index): ?string
    {
        /** @var ActiveRecord $model */
        if ($this->value === null) {
            $deviceTypes = $this->getDeviceNames();
            $attribute = $this->attribute;

            return $deviceTypes[$model->$attribute] ?? '';
        }

        return parent::getDataCellValue($model, $key, $index);
    }

    /**
     * @return array
     */
    protected function getDeviceNames(): array
    {
        static $deviceNames = [];

        $key = [
            __CLASS__,
            __METHOD__,
            __LINE__,
        ];

        if (empty($deviceNames)) {
            $data = Yii::$app->getCache()->get($key);
            if ($data === false) {
                $dependency = new TagDependency([
                    'tags' => [
                        DeviceName::class,
                        DeviceName::tableName(),
                    ],
                ]);

                $data = ArrayHelper::map(
                    DeviceName::find()->status()->hidden()->asArray()->all(),
                    'id',
                    static function (array $row) {
                        return $row['name'];
                    }
                );
                Yii::$app->getCache()->set($key, $data, null, $dependency);
            }

            $deviceNames = $data;
        }

        return $deviceNames;
    }
}
