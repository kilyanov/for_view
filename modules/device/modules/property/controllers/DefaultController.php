<?php

declare(strict_types=1);

namespace app\modules\device\modules\property\controllers;

use app\common\rbac\CollectionRolls;
use app\modules\device\models\DeviceProperty;
use app\modules\device\models\search\DevicePropertySearch;
use kilyanov\architect\controller\ApplicationController;
use yii\web\Response;

/**
 * Default controller for the `property` module
 */
class DefaultController extends ApplicationController
{
    /**
     * @return void
     */
    public function init(): void
    {
        $this->setModelClass(DeviceProperty::class);
        $this->setSearchModelClass(DevicePropertySearch::class);
        parent::init();
        $this->setListAccess([CollectionRolls::ROLE_VERIFIER]);
    }

    /**
     * @param string $deviceNameId
     * @param string|null $name
     * @return Response
     */
    public function actionList(string $deviceNameId, ?string $name = null): Response
    {
        $query = DeviceProperty::find()
            ->andWhere([DeviceProperty::tableName() . '.[[deviceNameId]]' => $deviceNameId])
            ->andFilterWhere(['like', DeviceProperty::tableName() . '.[[name]]', $name]);

        $result['results'] = array_map(
            function (DeviceProperty $model){
                return [
                    'id' => $model->id,
                    'text' => $model->getFullName(),
                ];
            },
            $query->hidden()
                ->status()
                ->limit(10)
                ->all());

        return $this->asJson($result);
    }
}
