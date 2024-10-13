<?php

declare(strict_types=1);

namespace app\modules\application\controllers;

use app\common\rbac\CollectionRolls;
use app\modules\application\actions\ReceiveAllAction;
use app\modules\application\actions\ReceiveCancelAllAction;
use app\modules\application\actions\ReceiveCancelCustomAction;
use app\modules\application\actions\ReceiveCustomAction;
use app\modules\application\models\Application;
use app\modules\application\models\ApplicationData;
use app\modules\application\models\ImportModel;
use app\modules\application\models\search\ApplicationDataSearch;
use kilyanov\architect\actions\base\ImportAction;
use kilyanov\architect\controller\ApplicationController;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 *
 * @property-read array $cfgParams
 */
class DataController extends ApplicationController
{
    /**
     * @return void
     * @throws NotFoundHttpException
     */
    public function init(): void
    {
        $this->setModelClass(ApplicationData::class);
        $this->setSearchModelClass(ApplicationDataSearch::class);
        parent::init();
        $this->setListAccess([CollectionRolls::ROLE_SECONDARY_CIL]);
        $this->layout = '/main-fluid';
        $cfgParams = $this->getCfgParams();
        $this->setCfgSearchModel($cfgParams);
        $this->setCfgModel($cfgParams);
    }

    /**
     * @return string
     */
    public function getImportModel(): string
    {
        return ImportModel::class;
    }

    /**
     * @return array|string[]
     */
    public function actions(): array
    {
        $actions = parent::actions();
        return ArrayHelper::merge(
            $actions,
            [
                'import' => [
                    'class' => ImportAction::class,
                    'fileMap' => [
                        ApplicationData::MARK_ONE => require (__DIR__ . '/../map/application_data_zip.php'),
                        ApplicationData::MARK_TWO => require (__DIR__ . '/../map/application_data_material.php'),
                    ],
                    'importModel' => ApplicationData::class,
                ],
                'receive-all' => [
                    'class' => ReceiveAllAction::class,
                ],
                'receive-cancel-all' => [
                    'class' => ReceiveCancelAllAction::class,
                ],
                'receive-custom' => [
                    'class' => ReceiveCustomAction::class,
                ],
                'receive-cancel-custom' => [
                    'class' => ReceiveCancelCustomAction::class,
                ],
            ]
        );
    }

    /**
     * @throws NotFoundHttpException
     */
    protected function getCfgParams(): array
    {
        $applicationId = Yii::$app->getRequest()->get('applicationId');
        if ($applicationId === null) return [];
        $model = Application::find()->ids($applicationId)->one();
        if ($model === null) {
            throw new NotFoundHttpException("Records with ID {$applicationId} not found.");
        }
        /** @var Application $model */
        return [
            'applicationId' => $model->id,
        ];
    }

    /**
     * @return array
     */
    public function getExportAttribute(): array
    {
        return [
            [
                'header' => 'Наименование',
                'attribute' => 'resourceId',
                'value' => function ($model) {
                    return $model->resourceRelation->getFullName();
                }
            ],
            [
                'header' => 'Заказано',
                'attribute' => 'quantity',
            ],
            [
                'header' => 'Срок поставки',
                'attribute' => 'deliveryTime',
            ],
            [
                'header' => 'Получено',
                'attribute' => 'quantityReceipt',
            ],
            [
                'header' => 'Дата получения',
                'attribute' => 'receiptDate',
            ],
            [
                'header' => 'Примечание',
                'attribute' => 'comment',
            ],
            [
                'header' => '% обеспечения',
                'format' => ['decimal', 2],
                'attribute' => 'percent',
            ]
        ];
    }
}
