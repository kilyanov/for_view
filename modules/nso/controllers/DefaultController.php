<?php

declare(strict_types=1);

namespace app\modules\nso\controllers;

use app\common\rbac\CollectionRolls;
use app\modules\nso\models\search\StandDateServiceSearch;
use app\modules\nso\models\search\StandSearch;
use app\modules\nso\models\Stand;
use kilyanov\architect\controller\ApplicationController;
use yii\bootstrap5\Html;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class DefaultController extends ApplicationController
{
    /**
     * @return void
     */
    public function init(): void
    {
        $this->setModelClass(Stand::class);
        $this->setSearchModelClass(StandSearch::class);
        parent::init();
        $this->setListAccess([CollectionRolls::ROLE_SECONDARY_CIL]);
        $this->layout = '/main-fluid';
    }

    /**
     * @param string $id
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionView(string $id): Response
    {
        $model = $this->findModel($id);

        $search = new StandDateServiceSearch(['standId' => $model->id]);
        $dataProvider = $search->search([]);

        $result = [
            'title' => 'Обслуживание стенда ' . $model->getFullName(),
            'content' => $this->renderAjax(
                'view', [
                    'dataProvider' => $dataProvider
                ]
            ),
            'footer' => Html::button('Закрыть', [
                'class' => 'btn btn-secondary pull-left',
                'data-bs-dismiss' => 'modal',
            ])
        ];

        return $this->asJson($result);
    }

    /**
     * @param $q
     * @param $id
     * @return Response
     */
    public function actionList($q = null, $id = null): Response
    {
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $models = Stand::find()
                ->andWhere([
                    'or',
                    ['like', Stand::tableName() . '.[[number]]', $q],
                    ['like', Stand::tableName() . '.[[inventoryNumber]]', $q],
                    ['like', Stand::tableName() . '.[[name]]', $q]
                ])
                ->hidden()
                ->limit(100)
                ->all();
            if (count($models)) {
                $data = [];
                foreach ($models as $model) {
                    /**
                     * @var Stand $model
                     */
                    $data[] = ['id' => $model->id, 'text' => $model->getFullName()];
                }
                $out['results'] = $data;
            }
        } elseif ($id > 0) {
            /**
             * @var Stand $m
             */
            $m = Stand::find()->ids($id)->one();
            $out['results'] = ['id' => $id, 'text' => $m->getFullName()];
        } else {
            $models = Stand::find()
                ->hidden()
                ->limit(100)
                ->all();
            if (count($models)) {
                $data = [];
                foreach ($models as $model) {
                    /**
                     * @var Stand $model
                     */
                    $data[] = ['id' => $model->id, 'text' => $model->getFullName()];
                }
                $out['results'] = $data;
            }
        }

        return $this->asJson($out);
    }

    /**
     * @return array
     */
    public function getExportAttribute(): array
    {
        return [
            [
                'header' => 'Примечание',
                'attribute' => 'description',
            ],
            [
                'header' => 'Подразделение',
                'attribute' => 'unitId',
                'value' => function ($model) {
                    return $model->unitRelation ?
                        $model->unitRelation->getFullName() : '';
                }
            ],
            [
                'header' => 'Название',
                'attribute' => 'name',
            ],
            [
                'header' => 'Номер',
                'attribute' => 'number',
            ],
            [
                'header' => 'Обозначение',
                'attribute' => 'mark',
            ],
            [
                'header' => 'Инвентарный номер',
                'attribute' => 'inventoryNumber',
            ],
            [
                'header' => 'Дата обслуживания',
                'attribute' => 'dateVerifications',
            ],
            [
                'header' => 'Н/Ч',
                'format' => ['decimal', 2],
                'attribute' => 'standardHours',
            ],
            [
                'header' => 'Категория',
                'attribute' => 'category',
            ],
            [
                'header' => 'Законсервирован',
                'attribute' => 'conservation:boolean',
                'value' => function ($model) {
                    return $model->conservation ?? '';
                }
            ],
        ];
    }
}
