<?php

declare(strict_types=1);

namespace app\modules\device\modules\type\controllers;

use app\common\rbac\CollectionRolls;
use app\modules\device\models\DeviceType;
use app\modules\device\models\search\DeviceTypeSearch;
use kilyanov\architect\controller\ApplicationController;
use yii\web\Response;

/**
 * Default controller for the `type` module
 */
class DefaultController extends ApplicationController
{
    /**
     * @return void
     */
    public function init(): void
    {
        $this->setModelClass(DeviceType::class);
        $this->setSearchModelClass(DeviceTypeSearch::class);
        parent::init();
        $this->setListAccess([CollectionRolls::ROLE_VERIFIER]);
    }

    /**
     * @param string|null $name
     * @return Response
     */
    public function actionList(?string $name = null): Response
    {
        $query = DeviceType::find()
            ->andFilterWhere(['like', DeviceType::tableName()  . '.[[name]]', $name]);
        $result['results'] = array_map(
            function (DeviceType $model) {
                return [
                    'id' => $model->id,
                    'text' => $model->getFullName()
                ];
            },
            $query->status()
                ->hidden()
                ->limit(50)
                ->all()
        );

        return $this->asJson($result);
    }
}
