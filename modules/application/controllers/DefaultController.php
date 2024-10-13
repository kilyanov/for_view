<?php

declare(strict_types=1);

namespace app\modules\application\controllers;

use app\common\rbac\CollectionRolls;
use app\modules\application\models\Application;
use app\modules\application\models\ApplicationData;
use app\modules\application\models\search\ApplicationDataSearch;
use app\modules\application\models\search\ApplicationSearch;
use kilyanov\architect\actions\base\CopyAction;
use kilyanov\architect\controller\ApplicationController;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;

/**
 * Default controller for the `application` module
 */
class DefaultController extends ApplicationController
{
    /**
     * @return void
     */
    public function init(): void
    {
        $this->setModelClass(Application::class);
        $this->setSearchModelClass(ApplicationSearch::class);
        parent::init();
        $this->setListAccess([CollectionRolls::ROLE_SECONDARY_CIL]);
        $this->layout = '/main-fluid';
    }

    /**
     * @return array|string[]
     */
    public function actions(): array
    {
        $actions = parent::actions();
        $actions['copy'] = [
            'class' => CopyAction::class,
            'copyRelations' => ['applicationDatasRelation'],
            'targetAttribute' => 'applicationId'
        ];

        return $actions;
    }

    /**
     * @param $action
     * @return bool
     * @throws BadRequestHttpException
     */
    public function beforeAction($action): bool
    {
        if (parent::beforeAction($action)) {
            if ($action->id == 'export') {
                $this->setSearchModelClass(ApplicationDataSearch::class);
                $search = new ApplicationSearch();
                $search->pageLimit = 1000;
                $dataProviderApplication = $search->search(Yii::$app->request->queryParams);
                $applications = ArrayHelper::map($dataProviderApplication->getModels(), 'id', 'id');
                $this->setCfgSearchModel(['applicationId' => $applications]);
            }

            return true;
        }

        return false;
    }

    /**
     * @return array
     */
    public function getExportAttribute(): array
    {
        return [
            [
                'header' => 'Заказ',
                'attribute' => 'orderId',
                'value' => function ($model) {
                    /** @var ApplicationData $model */
                    return $model->applicationRelation->orderRelation->number;
                }
            ],
            [
                'header' => 'Наименование',
                'attribute' => 'resourceId',
                'value' => function ($model) {
                    /** @var ApplicationData $model */
                    return $model->resourceRelation->getFullName();
                }
            ],
            [
                'header' => 'Ед. изм.',
                'attribute' => 'ed',
                'value' => function ($model) {
                    /** @var ApplicationData $model */
                    return $model->resourceRelation->ed;
                }
            ],
            [
                'header' => 'Заказано',
                'format' => ['decimal', 2],
                'attribute' => 'quantity',
            ],
            [
                'header' => 'Получено',
                'attribute' => 'quantityReceipt',
                'format' => ['decimal', 2],
                'value' => function ($model) {
                    /** @var ApplicationData $model */
                    return empty($model->quantityReceipt) ? 0.00 : $model->quantityReceipt;
                }
            ],
            [
                'header' => 'Обозначение',
                'attribute' => 'mark',
                'value' => function ($model) {
                    /** @var ApplicationData $model */
                    return $model->mark ? $model->getMark() : null;
                }
            ],
            [
                'header' => 'Тип',
                'attribute' => 'type',
                'value' => function ($model) {
                    /** @var ApplicationData $model */
                    return $model->type ? $model->getType() : null;
                }
            ],
            [
                'header' => 'Заявка',
                'attribute' => 'applicationId',
                'value' => function ($model) {
                    /** @var ApplicationData $model */
                    return $model->applicationRelation->getFullName();
                }
            ],
            [
                'header' => 'Изделие',
                'attribute' => 'productId',
                'value' => function ($model) {
                    /** @var ApplicationData $model */
                    return $model->applicationRelation->productRelation->getFullName();
                }
            ],
            [
                'header' => 'Контракт',
                'attribute' => 'contractId',
                'value' => function ($model) {
                    /** @var ApplicationData $model */
                    return $model->applicationRelation->orderRelation->contractRelation->getFullName();
                }
            ],
            [
                'header' => 'Статус',
                'attribute' => 'status',
            ],
        ];
    }
}
