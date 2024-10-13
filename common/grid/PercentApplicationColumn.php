<?php

declare(strict_types=1);

namespace app\common\grid;

use app\modules\application\models\ApplicationData;
use Exception;
use Yii;
use yii\caching\TagDependency;
use yii\db\ActiveRecord;
use yii\grid\DataColumn;

/**
 *
 * @property-read array $percents
 */
class PercentApplicationColumn extends DataColumn
{
    /**
     * @throws Exception
     */
    public function getDataCellValue($model, $key, $index): ?float
    {
        /** @var ActiveRecord $model */
        if ($this->value === null) {
            $percents = $this->getPercents();
            return $percents[$model->id] ?? 0.00;
        }

        return parent::getDataCellValue($model, $key, $index);
    }

    /**
     * @return array|float[]
     */
    protected function getPercents(): array
    {
        static $percents = [];

        $key = [
            __CLASS__,
            __METHOD__,
            __LINE__
        ];

        if (empty($percents)) {
            $data = Yii::$app->getCache()->get($key);
            if ($data === false) {
                $data = [];
                $dependency = new TagDependency([
                    'tags' => [
                        ApplicationData::class,
                        ApplicationData::tableName(),
                    ],
                ]);
                $models = ApplicationData::find()
                    ->select([
                        'count(*) as countData',
                        'SUM(quantityReceipt / quantity * 100) as sumData',
                        'applicationId'
                    ])
                    ->groupBy('applicationId')
                    ->asArray()
                    ->all();
                foreach ($models as $model) {
                    $id = $model['applicationId'];
                    $value = $model['countData'] === 0 ? 0.00 : (float)($model['sumData'] / $model['countData']);

                    $data[$id] = $value;
                }

                Yii::$app->getCache()->set($key, $data, null, $dependency);
            }

            $percents = $data;
        }

        return $percents;
    }
}
