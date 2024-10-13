<?php

declare(strict_types=1);

namespace app\modules\industry\modules\order\controllers;

use app\common\rbac\CollectionRolls;
use app\modules\industry\models\OrderList;
use app\modules\industry\models\search\OrderListSearch;
use kilyanov\architect\controller\ApplicationController;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Default controller for the `industry` module
 */
class DefaultController extends ApplicationController
{
    /**
     * @return void
     */
    public function init(): void
    {
        $this->setModelClass(OrderList::class);
        $this->setSearchModelClass(OrderListSearch::class);
        parent::init();
        $this->setListAccess([CollectionRolls::ROLE_SECONDARY_CIL]);
        $this->layout = '/main-fluid';
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionView(string $orderId): string
    {
        $model = $this->findModel($orderId);

        return $this->render('view', ['model' => $model]);
    }

    /**
     * @param string|null $number
     * @return Response
     */
    public function actionList(?string $number): Response
    {
        $query = OrderList::find()
            ->andFilterWhere(['like', OrderList::tableName()  . '.[[number]]', $number]);
        $result['results'] = array_map(
            function (OrderList $model) {
                return [
                    'id' => $model->id,
                    'text' => $model->getFullName()
                ];
            },
            $query->hidden()
                ->limit(20)
                ->all()
        );

        return $this->asJson($result);
    }
}
