<?php

declare(strict_types=1);

namespace app\common\grid;

use app\modules\device\models\DeviceProperty;
use Yii;
use yii\caching\TagDependency;
use yii\db\ActiveRecord;
use yii\grid\DataColumn;
use yii\helpers\ArrayHelper;

/**
 *
 * @property-read array $deviceProperties
 */
class DevicePropertyColumn extends DataColumn
{
    /**
     * @var string
     */
    public $attribute = 'devicePropertyId';

    /**
     * @var string
     */
    public string $relation = 'devicePropertyRelation';

    /**
     * @throws \Exception
     */
    public function getDataCellValue($model, $key, $index): ?string
    {
        /** @var ActiveRecord $model */
        if ($this->value === null) {
            $deviceProperties = $this->getDeviceProperties();
            $attribute = $this->attribute;

            return $deviceProperties[$model->$attribute] ?? '';
        }

        return parent::getDataCellValue($model, $key, $index);
    }

    /**
     * @return array
     */
    protected function getDeviceProperties(): array
    {
        static $deviceProperties = [];

        $key = [
            __CLASS__,
            __METHOD__,
            __LINE__,
        ];

        if (empty($deviceProperties)) {
            $data = Yii::$app->getCache()->get($key);
            if ($data === false) {
                $dependency = new TagDependency([
                    'tags' => [
                        DeviceProperty::class,
                        DeviceProperty::tableName(),
                    ],
                ]);

                $data = ArrayHelper::map(
                    DeviceProperty::find()->status()->hidden()->asArray()->all(),
                    'id',
                    static function (array $row) {
                        return $row['name'];
                    }
                );
                Yii::$app->getCache()->set($key, $data, null, $dependency);
            }

            $deviceProperties = $data;
        }

        return $deviceProperties;
    }

}
