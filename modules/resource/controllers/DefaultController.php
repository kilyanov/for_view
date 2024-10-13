<?php

declare(strict_types=1);

namespace app\modules\resource\controllers;

use app\modules\resource\models\Resource;
use app\modules\resource\models\search\ResourceSearch;
use kilyanov\architect\controller\ApplicationController;
use yii\web\Response;

class DefaultController extends ApplicationController
{
    /**
     * @return void
     */
    public function init(): void
    {
        parent::init();
        $this->setModelClass(Resource::class);
        $this->setSearchModelClass(ResourceSearch::class);
    }
    /**
     * @param string|null $q
     * @param string|null $id
     * @return Response
     */
    public function actionList(?string $q = null, ?string $id = null): Response
    {
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $models = Resource::find()
                ->andWhere(['or',
                    ['like', Resource::tableName() . '.[[name]]', $q],
                    ['like', Resource::tableName() . '.[[mark]]', $q],
                ])
                ->hidden()
                ->limit(20)
                ->all();
            if (count($models)) {
                $data = [];
                foreach ($models as $model) {
                    /** @var Resource $model */
                    $data[] = ['id' => $model->id, 'text' => $model->getFullName()];
                }
                $out['results'] = $data;
            }
        } elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => Resource::findOne($id)->getFullName()];
        }

        return $this->asJson($out);
    }
}
