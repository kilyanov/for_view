<?php

declare(strict_types=1);

namespace app\modules\device\controllers;

use app\common\rbac\CollectionRolls;
use app\modules\device\actions\FilterAction;
use app\modules\device\models\Device;
use app\modules\device\models\DeviceName;
use app\modules\device\models\DeviceProperty;
use app\modules\device\models\DeviceType;
use app\modules\device\models\search\DeviceSearch;
use Exception;
use kilyanov\architect\controller\ApplicationController;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 *
 * @property-read array $deviceTypes
 * @property-read array $deviceNames
 * @property-read array $deviceProperties
 */
class DefaultController extends ApplicationController
{
    /**
     * @return void
     */
    public function init(): void
    {
        $this->setModelClass(Device::class);
        $this->setSearchModelClass(DeviceSearch::class);
        parent::init();
        $this->setListAccess([CollectionRolls::ROLE_VERIFIER]);
        $this->layout = '/main-fluid';
    }

    /**
     * @return array
     */
    public function actions(): array
    {
        $actions = parent::actions();
        unset($actions['update']);
        $actions['filter'] = [
            'class' => FilterAction::class,
        ];
        return $actions;
    }

    /**
     * @param string $id
     * @return string|Response
     * @throws NotFoundHttpException
     * @throws Exception
     */
    public function actionUpdate(string $id): string|Response
    {
        /** @var Device $model */
        $model = $this->findModel($id);
        if (Yii::$app->request->isAjax) {
            return $this->redirect(Url::toRoute(['/device/default/update', 'id' => $id]));
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Данные по СИ обновлены.');
            $model->refresh();
        }

        return $this->render(
            'update',
            [
                'model' => $model,
                'forceReload' => $this->getForceReload()
            ]
        );
    }

    /**
     * @param string|null $number
     * @return Response
     * @throws Exception
     */
    public function actionList(?string $number = null): Response
    {
        $query = Device::find()->hidden();
        if(!empty($number)) {
            $query->andWhere([
                'or',
                ['like', Device::tableName() . '.[[factoryNumber]]', $number],
                [Device::tableName() . '.[[inventoryNumber]]' => $number],
            ]);
        }
        $result['results'] = array_map(
            function ($model){
                /** @var Device $model */
                return [
                    'id' => $model->id,
                    'text' => $model->getFullName() . ' (' . $model->deviceToUnitRelation?->unitRelation->getFullName() . ')',
                ];
            },
            $query->hidden()
                ->limit(50)
                ->all());

        return $this->asJson($result);
    }

    /**
     * @return array
     */
    public function getExportAttribute(): array
    {
        return [
            [
                'header' => 'Подразделение',
                'attribute' => 'unit',
                'value' => function ($model) {
                    return $model->deviceToUnitRelation ?
                        $model->deviceToUnitRelation->unitRelation->getFullName() : '';
                }
            ],
            [
                'attribute' => 'deviceNameId',
                'value' => function ($model) {
                    $deviceNames = $this->getDeviceNames();
                    return $deviceNames[$model->deviceNameId] ?? '';
                }
            ],
            [
                'attribute' => 'deviceTypeId',
                'format' => 'raw',
                'value' => function ($model) {
                    $deviceNames = $this->getDeviceTypes();
                    return $deviceNames[$model->deviceTypeId] ?? '';
                }
            ],
            [
                'attribute' => 'devicePropertyId',
                'format' => 'raw',
                'value' => function ($model) {
                    $deviceNames = $this->getDeviceProperties();
                    return $deviceNames[$model->devicePropertyId] ?? '';
                }
            ],
            [
                'format' => 'raw',
                'attribute' => 'stateRegister',
            ],
            [
                'attribute' => 'factoryNumber',
            ],
            [
                'format' => 'raw',
                'attribute' => 'inventoryNumber',
            ],
            [
                'attribute' => 'verificationPeriod',
                'value' => function ($model) {
                    return $model->getVerificationPeriod();
                }
            ],
            [
                'attribute' => 'verification',
                'value' => function ($model) {
                    return $model->deviceVerificationRelation->verification_date ?? '';
                }
            ],
            [
                'attribute' => 'verificationNext',
                'value' => function ($model) {
                    return $model->deviceVerificationRelation->nextVerification_date ?? '';
                }
            ],
            [
                'attribute' => 'reject',
                'value' => function ($model) {
                    return $model->deviceRejectionRelation->rejection_date ?? '';
                }
            ],
            [
                'format' => ['decimal', 2],
                'attribute' => 'norma',
            ],
            [
                'attribute' => 'category',
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getDeviceNames(): array
    {
        static $deviceNames = [];

        $key = [
            __CLASS__,
            __METHOD__,
            __LINE__,
            DeviceName::class
        ];
        if (empty($deviceNames)) {
            $data = Yii::$app->getCache()->get($key);
            if ($data === false) {
                $data = ArrayHelper::map(
                    DeviceName::find()->status()->hidden()->asArray()->all(),
                    'id',
                    static function (array $row) {
                        return $row['name'];
                    }
                );
                Yii::$app->getCache()->set($key, $data);
            }

            $deviceNames = $data;
        }

        return $deviceNames;
    }

    /**
     * @return array
     */
    protected function getDeviceTypes(): array
    {
        static $deviceTypes = [];

        $key = [
            __CLASS__,
            __METHOD__,
            __LINE__,
            DeviceType::class
        ];

        if (empty($deviceTypes)) {
            $data = Yii::$app->getCache()->get($key);
            if ($data === false) {
                $data = ArrayHelper::map(
                    DeviceType::find()->status()->hidden()->asArray()->all(),
                    'id',
                    static function (array $row) {
                        return $row['name'];
                    }
                );
                Yii::$app->getCache()->set($key, $data);
            }

            $deviceTypes = $data;
        }

        return $deviceTypes;
    }

    /**
     * @return array
     */
    protected function getDeviceProperties(): array
    {
        static $deviceProperties = [];

        $key = [
            __CLASS__,
            __METHOD__,
            __LINE__,
            DeviceProperty::class
        ];

        if (empty($deviceProperties)) {
            $data = Yii::$app->getCache()->get($key);
            if ($data === false) {

                $data = ArrayHelper::map(
                    DeviceProperty::find()->status()->hidden()->asArray()->all(),
                    'id',
                    static function (array $row) {
                        return $row['name'];
                    }
                );
                Yii::$app->getCache()->set($key, $data);
            }

            $deviceProperties = $data;
        }

        return $deviceProperties;
    }
}
