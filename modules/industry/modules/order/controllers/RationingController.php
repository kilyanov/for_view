<?php

declare(strict_types=1);

namespace app\modules\industry\modules\order\controllers;

use app\common\rbac\CollectionRolls;
use app\modules\industry\models\OrderRationing;
use app\modules\industry\models\search\OrderRationingSearch;
use app\modules\industry\traits\CommonControllerTrait;;
use kilyanov\architect\controller\ApplicationController;
use Yii;
use yii\web\Response;

class RationingController extends ApplicationController
{
    use CommonControllerTrait;

    /**
     * @return void
     */
    public function init(): void
    {
        $this->setModelClass(OrderRationing::class);
        $this->setSearchModelClass(OrderRationingSearch::class);
        parent::init();
        $this->setListAccess([CollectionRolls::ROLE_SECONDARY_CIL]);
        $this->layout = '/main-fluid';
    }

    /**
     * @param string $orderId
     * @param string|null $name
     * @return Response
     */
    public function actionList(string $orderId, ?string $name = null): Response
    {
        $query = OrderRationing::find()
            ->andWhere([
                OrderRationing::tableName() . '.[[orderId]]' => $orderId,
                OrderRationing::tableName() . '.[[unitId]]' => Yii::$app->user->identity->unitId
            ])
            ->andFilterWhere(['like', OrderRationing::tableName() . '.[[name]]', $name]);

        $result['results'] = array_map(
            function ($model) {
                /** @var OrderRationing $model */
                return [
                    'id' => $model->id,
                    'text' => $model->getFullName(),
                ];
            },
            $query->hidden()
                ->limit(10)
                ->all());

        return $this->asJson($result);
    }
}
