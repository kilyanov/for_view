<?php

declare(strict_types=1);

namespace app\commands;

use app\modules\industry\models\Machine;
use app\modules\rationing\models\RationingProduct;
use app\modules\rationing\models\RationingProductData;
use Exception;
use Yii;
use app\common\rbac\CollectionRolls;
use yii\console\Controller;
use yii\helpers\FileHelper;

class TestController extends Controller
{
    /**
     * @throws Exception
     */
    public function actionIndex()
    {
        $dir = Yii::getAlias('@runtime/export');
        if (!is_dir($dir)) {
            FileHelper::createDirectory($dir);
        }
        copy(
            Yii::getAlias('@templates') . DIRECTORY_SEPARATOR . 'rationing_product.xls',
            $dir . DIRECTORY_SEPARATOR . 'rationing_product.xls'
        );
    }

    /**
     * @param string $id
     * @return void 'c4d59453-61a5-4028-9d96-40747407e83f'
     * @throws Exception
     */
    public function actionRationing(string $id = '0191632a-0412-79a1-a8d7-9d7e7c60b9e8'): void
    {
        $rationing = RationingProduct::findOne($id);
        $machines = Machine::find()
            ->select('id')
            ->where(['productId' => $rationing->productRelation->id])
            ->order()
            ->column();
        $position = 0;
        foreach ($machines as $machine) {
            $models = RationingProductData::find()
                ->andWhere([
                    'rationingId' => $id,
                    'machineId' => $machine
                ])
                ->orderBy([
                    'point' => SORT_ASC,
                    'subItem' => SORT_ASC,
                ])
                ->all();
            foreach ($models as $model) {
                $model->sort = $position;
                $model->save(false);
                $position++;
            }
            $position++;
        }
    }
}
