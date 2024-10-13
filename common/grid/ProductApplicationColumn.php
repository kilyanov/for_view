<?php

declare(strict_types=1);

namespace app\common\grid;

use app\modules\application\models\Application;
use Exception;
use Yii;
use yii\caching\TagDependency;
use yii\db\ActiveRecord;
use yii\grid\DataColumn;

/**
 *
 * @property-read string[] $products
 */
class ProductApplicationColumn extends DataColumn
{
    /**
     * @var string
     */
    public $attribute = 'productId';

    /**
     * @throws Exception
     */
    public function getDataCellValue($model, $key, $index): ?string
    {
        /** @var ActiveRecord $model */
        if ($this->value === null) {
            $products = $this->getProducts();

            return $products[$model->id] ?? '';
        }

        return parent::getDataCellValue($model, $key, $index);
    }

    /**
     * @return array|string[]
     */
    protected function getProducts(): array
    {
        static $products = [];

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
                        Application::class,
                        Application::tableName(),
                    ],
                ]);
                $models = Application::find()->hidden()->all();
                foreach ($models as $model) {
                    /** @var Application $model */
                   $data[$model->id] = $model->productRelation->getFullName();
                }

                Yii::$app->getCache()->set($key, $data, null, $dependency);
            }

            $products = $data;
        }

        return $products;
    }
}
