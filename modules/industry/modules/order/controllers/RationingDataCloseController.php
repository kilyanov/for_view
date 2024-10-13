<?php

declare(strict_types=1);

namespace app\modules\industry\modules\order\controllers;

use app\common\rbac\CollectionRolls;
use app\modules\industry\models\OrderRationingData;
use app\modules\industry\models\OrderRationingDataClose;
use app\modules\industry\models\search\OrderRationingDataCloseSearch;
use kilyanov\architect\controller\ApplicationController;
use kilyanov\architect\factory\ButtonCloseFactory;
use kilyanov\architect\interfaces\AnswerInterface;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class RationingDataCloseController extends ApplicationController
{

    /**
     * @return void
     * @throws NotFoundHttpException
     */
    public function init(): void
    {
        $this->setModelClass(OrderRationingDataClose::class);
        $this->setSearchModelClass(OrderRationingDataCloseSearch::class);
        $this->setForceReload(AnswerInterface::DEFAULT_FORCE_RELOAD . '-close');
        $cfgParams = $this->getCfgParams();
        $this->setCfgSearchModel($cfgParams);
        $this->setCfgModel($cfgParams);
        parent::init();
        $this->setListAccess([CollectionRolls::ROLE_SECONDARY_CIL]);
        $this->layout = '/main-fluid';
    }

    /**
     * @throws NotFoundHttpException
     */
    protected function getCfgParams(): array
    {
        $orderRationingDataId = Yii::$app->getRequest()->get('id');
        if ($orderRationingDataId === null) {
            return [];
        }
        /** @var OrderRationingData $model */
        $model = OrderRationingData::find()->ids($orderRationingDataId)->hidden()->one();
        if ($model === null) {
            throw new NotFoundHttpException("Records with ID {$orderRationingDataId} not found.");
        }
        return [
            'orderRationingDataId' => $model->id,
        ];
    }

    /**
     * @return array
     */
    public function actions(): array
    {
        return [];
    }

    /**
     * @param string $id
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionIndex(string $id): array
    {
        $searchClass = $this->searchModelClass;
        $search = new $searchClass($this->getCfgParams());
        $this
            ->getAnswer()
            ->getFooter()
            ->setItems(ButtonCloseFactory::create());
        Yii::$app->response->format = Response::FORMAT_JSON;

        return [
            'title' => 'Просмотр данных',
            'content' => $this->renderAjax(
                'index',
                [
                    'model' => $search,
                    'dataProvider' => $search->search(Yii::$app->request->queryParams),
                    'forceReload' => $this->getForceReload(),
                ]
            ),
            'footer' => $this->getAnswer()->getFooter()->getData()
        ];
    }
}
