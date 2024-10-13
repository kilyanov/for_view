<?php

declare(strict_types=1);

namespace app\modules\device\trait;

use kilyanov\architect\controller\ApplicationController;
use Yii;
use app\modules\device\models\Device;
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
            if ($action->id === 'index') {
                $this->setCfgSearchModel($cfgParams);
                $this->setCfgModel($cfgParams);
                $this->setTypeRender(ApplicationController::TYPE_RENDER_PARTIAL);
            }
            if ($action->id === 'create') {
                $this->setCfgModel($cfgParams);
            }

            return true;
        } else {

            return false;
        }
    }

    /**
     * @param string|null $action
     * @return array
     * @throws NotFoundHttpException
     */
    protected function getCfgParams(?string $action = null): array
    {
        $params = [];
        if ($action === 'create' || $action === 'index') {
            $id = $action === 'index' ?
                Yii::$app->getRequest()->get('id') :
                Yii::$app->getRequest()->get('deviceId');
            /** @var Device $model */
            $model = Device::find()->ids($id)->one();
            $params = ['deviceId' => $model->id];
            if ($model === null) {
                throw new NotFoundHttpException("Records with ID {$id} not found.");
            }
        }

        return $params;
    }
}
