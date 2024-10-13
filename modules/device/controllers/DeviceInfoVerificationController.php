<?php

declare(strict_types=1);

namespace app\modules\device\controllers;

use app\common\rbac\CollectionRolls;
use app\modules\device\models\Device;
use app\modules\device\models\DeviceInfoVerification;
use kilyanov\architect\controller\ApplicationController;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

/**
 * DeviceInfoVerification controller for the `reference` module
 */
class DeviceInfoVerificationController extends ApplicationController
{
    /**
     * @return void
     */
    public function init(): void
    {
        $this->setModelClass(DeviceInfoVerification::class);
        parent::init();
        $this->setListAccess([CollectionRolls::ROLE_VERIFIER]);
    }

    /**
     * @return array
     */
    public function actions(): array
    {
        $actions = parent::actions();
        unset($actions['index'], $actions['delete-all']);

        return $actions;
    }

    /**
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     */
    public function beforeAction($action): bool
    {
        if (parent::beforeAction($action)) {
            $cfgParams = $this->getCfgParams($action->id);
            $this->setCfgModel($cfgParams);
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
        if ($action === 'create') {
            $id = Yii::$app->getRequest()->get('deviceId');
            $model = Device::find()->ids($id)->one();
        } else {
            $id = Yii::$app->getRequest()->get('id');
            $model = DeviceInfoVerification::find()->ids($id)->one();
        }
        if ($model === null) {
            throw new NotFoundHttpException("Records with ID {$id} not found.");
        }
        /** @var DeviceInfoVerification|Device $model */
        return [
            'deviceId' => $model instanceof Device ? $model->id : $model->deviceId
        ];
    }
}
