<?php

declare(strict_types=1);

namespace app\modules\rationing\controllers;

use app\common\rbac\CollectionRolls;
use app\modules\rationing\models\RationingDevice;
use app\modules\rationing\models\search\RationingDeviceSearch;
use kilyanov\architect\controller\ApplicationController;
use yii\web\Response;

class DeviceController extends ApplicationController
{
    /**
     * @return void
     */
    public function init(): void
    {
        $this->setModelClass(RationingDevice::class);
        $this->setSearchModelClass(RationingDeviceSearch::class);
        parent::init();
        $this->setListAccess([CollectionRolls::ROLE_SECONDARY_CIL]);
        $this->layout = '/main-fluid';
    }

    /**
     * @param string|null $name
     * @return Response
     */
    public function actionList(?string $name = null): Response
    {
        $query = RationingDevice::find()
            ->andFilterWhere(['like', RationingDevice::tableName() . '.[[name]]', $name]);

        $result['results'] = array_map(
            function ($model){
                /** @var RationingDevice $model */
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
