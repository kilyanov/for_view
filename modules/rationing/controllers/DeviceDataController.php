<?php

declare(strict_types=1);

namespace app\modules\rationing\controllers;

use app\common\rbac\CollectionRolls;
use app\modules\rationing\models\RationingDevice;
use app\modules\rationing\models\RationingDeviceData;
use app\modules\rationing\models\search\RationingDeviceDataSearch;
use kilyanov\architect\controller\ApplicationController;
use Yii;
use yii\web\NotFoundHttpException;

/**
 *
 * @property-read array $cfgParams
 */
class DeviceDataController extends ApplicationController
{
    /**
     * @return void
     * @throws NotFoundHttpException
     */
    public function init(): void
    {
        $this->setModelClass(RationingDeviceData::class);
        $this->setSearchModelClass(RationingDeviceDataSearch::class);
        parent::init();
        $this->setListAccess([CollectionRolls::ROLE_SECONDARY_CIL]);
        $this->layout = '/main-fluid';
        $cfgParams = $this->getCfgParams();
        $this->setCfgSearchModel($cfgParams);
        $this->setCfgModel($cfgParams);
    }

    /**
     * @throws NotFoundHttpException
     */
    protected function getCfgParams(): array
    {
        $rationingId = Yii::$app->getRequest()->get('rationingId');
        if ($rationingId === null) return [];
        $model = RationingDevice::find()->ids($rationingId)->one();
        /** @var $model RationingDevice */
        if ($model === null) {
            throw new NotFoundHttpException("Records with ID {$rationingId} not found.");
        }
        return [
            'rationingDeviceId' => $model->id,
        ];
    }
}
