<?php

declare(strict_types=1);

namespace app\modules\industry\modules\order\actions;

use app\modules\industry\models\OrderRationingDataClose;
use kilyanov\architect\actions\base\BaseAction;
use kilyanov\architect\controller\ApplicationController;
use Yii;
use yii\base\InvalidConfigException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class DeleteCloseNormaAction extends BaseAction
{
    /**
     * @throws NotFoundHttpException
     */
    public function run(): array
    {
        /** @var ApplicationController $controller */
        $controller = $this->controller;
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($this->getModel()->delete()) {
                return $controller->getAnswer()->isDelete();
            } else {
                $this->setErrorSaveData();
                return $controller->getAnswer()->isPost();
            }
        }

        throw new NotFoundHttpException('Request is not Ajax.');
    }

    /**
     * @throws InvalidConfigException
     * @throws NotFoundHttpException
     */
    public function runWithParams($params)
    {
        /** @var ApplicationController $controller */
        $controller = $this->controller;
        $id = (string)$params['id'];
        $model = OrderRationingDataClose::find()
            ->andWhere(['orderRationingDataId' => $id])
            ->orderBy([
                'year' => SORT_DESC,
                'month' => SORT_DESC
            ])
            ->one();
        if ($model === null) {
            throw new NotFoundHttpException(
                "Records OrderRationingDataClose with orderRationingDataId {$id} not found."
            );
        }
        $this->setModel($model);
        $controller->getAnswer()
            ->setContainerReload($controller->getForceReload())
            ->setTitle('Отмена последнего списания');

        return parent::runWithParams($params);
    }
}
