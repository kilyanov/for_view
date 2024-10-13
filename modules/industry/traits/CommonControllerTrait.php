<?php

declare(strict_types=1);

namespace app\modules\industry\traits;

use app\modules\industry\models\OrderList;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

trait CommonControllerTrait
{
    /**
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     */
    public function beforeAction($action): bool
    {
        if (parent::beforeAction($action)) {
            $cfgParams = $this->getCfgParams($action->id);
            $this->setCfgModel($cfgParams);
            $this->setCfgSearchModel($cfgParams);

            return true;
        }

        return false;
    }

    /**
     * @param string|null $action
     * @return array
     * @throws NotFoundHttpException
     */
    protected function getCfgParams(?string $action = null): array
    {
        $params = [];
        if ($action === 'create' || $action === 'index' || $action === 'move') {
            $id = Yii::$app->getRequest()->get('orderId');
            /** @var OrderList $model */
            $model = OrderList::find()->ids($id)->one();
            if ($model === null) {
                throw new NotFoundHttpException("Records with ID {$id} not found.");
            }
            $params = ['orderId' => $model->id];
        }

        return $params;
    }
}
