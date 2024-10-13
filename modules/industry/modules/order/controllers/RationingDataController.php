<?php

declare(strict_types=1);

namespace app\modules\industry\modules\order\controllers;

use app\common\rbac\CollectionRolls;
use app\modules\industry\models\OrderRationing;
use app\modules\industry\models\OrderRationingData;
use app\modules\industry\models\search\OrderRationingDataSearch;
use app\modules\industry\modules\order\actions\AllCancelCloseNormaAction;
use app\modules\industry\modules\order\actions\AllCloseNormaAction;
use app\modules\industry\modules\order\actions\CloseNormaAction;
use app\modules\industry\modules\order\actions\DeleteCloseNormaAction;
use app\modules\industry\modules\order\actions\SetNewNumberItemAction;
use app\modules\industry\modules\order\actions\UpdateLastCloseNormaAction;
use kilyanov\architect\actions\base\ImportAction;
use kilyanov\architect\controller\ApplicationController;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 *
 * @property-read array $cfgParams
 */
class RationingDataController extends ApplicationController
{
    /**
     * @return void
     * @throws NotFoundHttpException
     */
    public function init(): void
    {
        $this->setModelClass(OrderRationingData::class);
        $this->setSearchModelClass(OrderRationingDataSearch::class);
        parent::init();
        $this->setListAccess([CollectionRolls::ROLE_SECONDARY_CIL]);
        $this->layout = '/main-fluid';
        $cfgParams = $this->getCfgParams();
        $this->setCfgSearchModel($cfgParams);
        $this->setCfgModel($cfgParams);
    }

    /**
     * @return array|string[]
     */
    public function actions(): array
    {
        $actions = parent::actions();

        return ArrayHelper::merge([
            'close-norma' => [
                'class' => CloseNormaAction::class,
            ],
            'cancel-close-norma' => [
                'class' => DeleteCloseNormaAction::class,
            ],
            'update-last-close-norma' => [
                'class' => UpdateLastCloseNormaAction::class,
            ],
            'all-close-norma' => [
                'class' => AllCloseNormaAction::class,
            ],
            'all-cancel-close-norma' => [
                'class' => AllCancelCloseNormaAction::class,
            ],
            'set-newNumber-item' => [
                'class' => SetNewNumberItemAction::class,
            ],
            'import' => [
                'class' => ImportAction::class,
                'fileMap' => require (__DIR__ . '/../map/rationing_data.php'),
                'importModel' => OrderRationingData::class,
            ],

        ], $actions);
    }

    /**
     * @throws NotFoundHttpException
     */
    protected function getCfgParams(): array
    {
        $rationingId = Yii::$app->getRequest()->get('rationingId');
        if ($rationingId === null) return [];
        $model = OrderRationing::find()->ids($rationingId)->one();
        /** @var $model OrderRationing */
        if ($model === null) {
            throw new NotFoundHttpException("Records with ID {$rationingId} not found.");
        }
        return [
            'rationingId' => $model->id,
        ];
    }

    /**
     * @param string $orderRationingId
     * @param string|null $name
     * @return Response
     */
    public function actionList(string $orderRationingId, ?string $name = null): Response
    {
        $query = OrderRationingData::find()
            ->andWhere([
                OrderRationingData::tableName() . '.[[rationingId]]' => $orderRationingId,
                OrderRationingData::tableName() . '.[[type]]' => [OrderRationingData::TYPE_POINT]//, OrderRationingData::TYPE_SUB_POINT
            ])->orderBy([OrderRationingData::tableName() . '.[[sort]]' => SORT_ASC]);
        $query->andFilterWhere(['like', OrderRationingData::tableName() . '.[[name]]', $name]);
        $result['results'] = array_map(
            function ($model) {
                /** @var OrderRationingData $model */
                return [
                    'id' => $model->id,
                    'text' => $model->point . '. ' . $model->name,
                ];
            },
            $query->hidden()
                ->limit(100)
                ->all()
        );

        return $this->asJson($result);
    }
}
