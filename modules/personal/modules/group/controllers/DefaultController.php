<?php

declare(strict_types=1);

namespace app\modules\personal\modules\group\controllers;

use app\modules\personal\modules\group\models\PersonalGroup;
use app\modules\personal\modules\group\models\search\PersonalGroupSearch;
use Exception;
use kilyanov\architect\controller\ApplicationController;
use yii\web\Response;

/**
 * Default controller for the `group` module
 */
class DefaultController extends ApplicationController
{
    /**
     * @return void
     */
    public function init(): void
    {
        $this->setModelClass(PersonalGroup::class);
        $this->setSearchModelClass(PersonalGroupSearch::class);
        parent::init();
    }

    /**
     * @param string $unitId
     * @return Response
     * @throws Exception
     */
    public function actionList(string $unitId): Response
    {
        $query = PersonalGroup::find()
            ->select([
                'id',
                'text' => 'name',
            ])
            ->andFilterWhere(['unitId' => $unitId])
            ->hidden();

        $result['results'] = $query
            ->limit(10)
            ->asArray()
            ->all();

        return $this->asJson($result);
    }
}
