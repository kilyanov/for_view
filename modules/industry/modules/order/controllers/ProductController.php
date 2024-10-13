<?php

declare(strict_types=1);

namespace app\modules\industry\modules\order\controllers;

use app\common\rbac\CollectionRolls;
use app\modules\industry\models\OrderToProduct;
use app\modules\industry\models\RepairProduct;
use app\modules\industry\models\search\OrderToProductSearch;
use app\modules\industry\traits\CommonControllerTrait;
use Exception;
use kilyanov\architect\controller\ApplicationController;
use yii\web\Response;

class ProductController extends ApplicationController
{
    use CommonControllerTrait;

    /**
     * @return void
     */
    public function init(): void
    {
        $this->setModelClass(OrderToProduct::class);
        $this->setSearchModelClass(OrderToProductSearch::class);
        parent::init();
        $this->setListAccess([CollectionRolls::ROLE_SECONDARY_CIL]);
        $this->layout = '/main-fluid';
    }

    /**
     * @param string $orderId
     * @param string|null $number
     * @return Response
     * @throws Exception
     */
    public function actionList(string $orderId, ?string $number = null): Response
    {
        $query = OrderToProduct::find()
            ->andWhere([OrderToProduct::tableName() . '.[[orderId]]' => $orderId])->joinWith(['productRelation']);
        if(!empty($number)) {
            $query->andWhere(['like', RepairProduct::tableName() . '.[[number]]', $number]);
        }
        $result['results'] = array_map(
            function ($model){
                /** @var OrderToProduct $model */
                return [
                    'id' => $model->id,
                    'text' => $model->getFullName(),
                ];
            },
            $query->limit(10)->all());

        return $this->asJson($result);
    }
}
