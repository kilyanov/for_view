<?php

declare(strict_types=1);

namespace app\modules\institution\controllers;

use app\modules\institution\models\Institution;
use app\modules\institution\models\search\InstitutionSearch;
use kilyanov\architect\controller\ApplicationController;
use yii\web\Response;

class DefaultController extends ApplicationController
{
    /**
     * @return void
     */
    public function init(): void
    {
        $this->setModelClass(Institution::class);
        $this->setSearchModelClass(InstitutionSearch::class);
        parent::init();
    }

    /**
     * @param string|null $name
     * @return Response
     */
    public function actionList(?string $name = null): Response
    {
        $query = Institution::find()
            ->select([
                'id',
                'text' => 'name',
            ])
            ->andFilterWhere(['like', 'name', $name])
            ->hidden();

        $result['results'] = $query
            ->limit(10)
            ->asArray()
            ->all();

        return $this->asJson($result);
    }
}
