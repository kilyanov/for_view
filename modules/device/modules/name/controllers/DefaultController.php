<?php

declare(strict_types=1);

namespace app\modules\device\modules\name\controllers;

use app\common\rbac\CollectionRolls;
use app\modules\device\interface\StatusAttributeInterface;
use app\modules\device\models\DeviceName;
use app\modules\device\models\search\DeviceNameSearch;
use kilyanov\architect\controller\ApplicationController;
use yii\web\Response;

/**
 * Default controller for the `name` module
 */
class DefaultController extends ApplicationController
{
    /**
     * @return void
     */
    public function init(): void
    {
        $this->setModelClass(DeviceName::class);
        $this->setSearchModelClass(DeviceNameSearch::class);
        parent::init();
        $this->setListAccess([CollectionRolls::ROLE_VERIFIER]);
    }

    /**
     * @param string $deviceTypeId
     * @param string|null $name
     * @return Response
     */
    public function actionList(string $deviceTypeId, ?string $name = null): Response
    {
        $query = DeviceName::find()
            ->andWhere([DeviceName::tableName() . '.[[deviceTypeId]]' => $deviceTypeId])
            ->andFilterWhere(['like', DeviceName::tableName() . '.[[name]]', $name]);

        $result['results'] = array_map(
            function (DeviceName $model) {
                return [
                    'id' => $model->id,
                    'text' => $model->name
                ];
            },
            $query->hidden()
                ->status(StatusAttributeInterface::STATUS_ACTIVE)
                ->limit(50)
                ->all());

        return $this->asJson($result);
    }
}
