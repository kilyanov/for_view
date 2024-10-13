<?php

declare(strict_types=1);

namespace app\modules\industry\modules\order\controllers;

use app\common\rbac\CollectionRolls;
use app\modules\industry\models\OrderRationingDataClose;
use app\modules\industry\models\search\OrderListSearch;
use app\modules\industry\models\search\WriteOffNormaSearch;
use kilyanov\architect\controller\ApplicationController;
use Yii;

class WriteOffNormaController extends ApplicationController
{
    /**
     * @return void
     */
    public function init(): void
    {
        $this->setModelClass(OrderRationingDataClose::class);
        $this->setSearchModelClass(WriteOffNormaSearch::class);
        parent::init();
        $this->setListAccess([CollectionRolls::ROLE_SECONDARY_CIL]);
    }

    /**
     * @return array|array[]
     */
    public function actions(): array
    {
        return [];
    }

    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $pageLimit = Yii::$app->getRequest()->get('pageLimit',20);
        $search = new WriteOffNormaSearch(['pageLimit' => $pageLimit]);
        $dataProvider = $search->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'model' => $search,
            'dataProvider' => $dataProvider,
            'forceReload' => $this->getAnswer()->getContainerReload(),
        ]);
    }

    /**
     * @return string
     */
    public function actionOrder(): string
    {
        $pageLimit = Yii::$app->getRequest()->get('pageLimit',20);
        $search = new OrderListSearch(['pageLimit' => $pageLimit]);
        $dataProvider = $search->search(Yii::$app->request->queryParams);

        return $this->render('order', [
            'model' => $search,
            'dataProvider' => $dataProvider,
            'forceReload' => $this->getAnswer()->getContainerReload(),
        ]);
    }

    /**
     * @return string
     */
    public function actionMonth(): string
    {
        $pageLimit = Yii::$app->getRequest()->get('pageLimit',20);
        $search = new WriteOffNormaSearch(['pageLimit' => $pageLimit]);
        $dataProvider = $search->searchMonth(Yii::$app->request->queryParams);

        return $this->render('month', [
            'model' => $search,
            'dataProvider' => $dataProvider,
            'forceReload' => $this->getAnswer()->getContainerReload(),
        ]);
    }
}
