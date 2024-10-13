<?php

declare(strict_types=1);

namespace app\modules\industry\modules\order\actions;

use app\modules\industry\models\OrderRationingDataClose;
use kilyanov\architect\actions\base\BaseAction;
use kilyanov\architect\controller\ApplicationController;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class AllCancelCloseNormaAction extends BaseAction
{
    /**
     * @throws NotFoundHttpException
     */
    public function run(): array
    {
        $request = Yii::$app->request;
        Yii::$app->response->format = Response::FORMAT_JSON;
        /** @var ApplicationController $controller */
        $controller = $this->controller;
        if ($request->isAjax) {
            $params = explode(',', $request->post('ids'));
            OrderRationingDataClose::deleteAll(['orderRationingDataId' => $params]);
            return $controller->getAnswer()->isDelete();
        } else {
            throw new NotFoundHttpException('Request is not Ajax.');
        }
    }
}
