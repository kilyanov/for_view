<?php

declare(strict_types=1);

namespace app\modules\industry\modules\presentation\controllers;

use app\common\rbac\CollectionRolls;
use app\modules\industry\models\PresentationBook;
use app\modules\industry\models\search\PresentationBookSearch;
use app\modules\personal\models\Personal;
use Exception;
use kilyanov\architect\controller\ApplicationController;
use Yii;
use yii\web\BadRequestHttpException;

/**
 *
 * @property-read array $cfgParams
 */
class DefaultController extends ApplicationController
{
    public function init(): void
    {
        $this->setModelClass(PresentationBook::class);
        $this->setSearchModelClass(PresentationBookSearch::class);
        parent::init();
        $this->setListAccess([CollectionRolls::ROLE_ENGINEER_CIL]);
        $this->layout = '/main-fluid';
    }

    /**
     * @return string
     * @throws Exception
     */
    public function actionChart(): string
    {
        $model = new PresentationBookSearch();
        $model->load(Yii::$app->request->getQueryParams());
        if (empty($model->month)) {
            $model->month = (int)date('m');
        }
        if (empty($model->year)) {
            $model->year = (int)date('Y');
        }
        $query = PresentationBook::find()
            ->joinWith(['personalRelation']);
        if ($model->typeView === PresentationBookSearch::TYPE_COUNT_PRESENT) {
            $query->select([
                Personal::tableName() . '.[[fistName]]',
                Personal::tableName() . '.[[lastName]]',
                Personal::tableName() . '.[[secondName]]',
                'count(*) as present'
            ]);
        }
        if ($model->typeView === PresentationBookSearch::TYPE_COUNT_NORMA) {
            $query->select([
                Personal::tableName() . '.[[fistName]]',
                Personal::tableName() . '.[[lastName]]',
                Personal::tableName() . '.[[secondName]]',
                'sum(norma) as present'
            ]);
        }
        $listPresent = $query->andWhere([
            PresentationBook::tableName() . '.[[year]]' => $model->year,
            PresentationBook::tableName() . '.[[month]]' => $model->month
        ])
            ->groupBy(['fistName', 'lastName', 'secondName'])
            ->asArray()->all();
        $series = array_map(function ($item) {
            return [
                'name' => Personal::getFullNameMoving($item),
                'data' => [(float)$item['present']]
            ];
        }, $listPresent);

        return $this->render('chart', [
            'series' => $series,
            'model' => $model
        ]);
    }

    /**
     * @param $action
     * @return bool
     * @throws BadRequestHttpException
     */
    public function beforeAction($action): bool
    {
        if (parent::beforeAction($action)) {
            if ($action->id === 'index' || $action->id === 'create') {
                $cfgParams = $this->getCfgParams();
                $this->setCfgSearchModel($cfgParams);
                $this->setCfgModel($cfgParams);
            }

            return true;
        } else {

            return false;
        }
    }

    /**
     * @return array
     */
    protected function getCfgParams(): array
    {
        $groupId = Yii::$app->getRequest()->get('groupId');
        $typeOrder = Yii::$app->getRequest()->get('typeOrder');

        return [
            'groupId' => $groupId,
            'typeOrder' => $typeOrder
        ];
    }

    /**
     * @return array
     */
    public function getExportAttribute(): array
    {
        return [
            [
                'attribute' => 'orderId',
                'value' => function ($model) {
                    /** @var PresentationBook $model */
                    return $model->orderRelation->number;
                }
            ],
            [
                'attribute' => 'personalId',
                'value' => function ($model) {
                    return $model->personalRelation->getFullName();
                },

            ],
            [
                'attribute' => 'impactId',
                'value' => function ($model) {
                    return $model->impactRelation->getFullName();
                },
            ],
            [
                'attribute' => 'unitId',
                'value' => function ($model) {
                    return $model->unitRelation->getFullName();
                },
            ],
            [
                'attribute' => 'name',
            ],
            [
                'attribute' => 'number',
            ],
            [
                'attribute' => 'inventoryNumber',
            ],
            [
                'header' => 'Владелец',
                'value' => function ($model) {
                    /** @var PresentationBook $model */
                    if ($model->deviceRepairRelation) {
                        return $model->deviceRepairRelation->deviceToUnitRelation->unitRelation->getFullName();
                    }
                    /** @var PresentationBook $model */
                    if ($model->deviceVerificationRelation) {
                        return $model->deviceVerificationRelation->deviceToUnitRelation->unitRelation->getFullName();
                    }
                    return '';
                },
            ],
            [
                'format' => ['decimal', 2],
                'attribute' => 'norma',
            ],
            [
                'attribute' => 'date',
                'value' => function ($model) {
                    return Yii::$app->getFormatter()->asDate($model->date, 'php:d.m.Y');
                },
            ],
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return $model->getStatus();
                },
            ],
            [
                'attribute' => 'comment',
            ],
        ];
    }
}
