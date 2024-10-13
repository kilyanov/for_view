<?php

declare(strict_types=1);

namespace app\modules\industry\modules\order\controllers;

use app\common\rbac\CollectionRolls;
use app\modules\application\actions\ReceiveAllAction;
use app\modules\application\actions\ReceiveCancelAllAction;
use app\modules\application\actions\ReceiveCancelCustomAction;
use app\modules\application\actions\ReceiveCustomAction;
use app\modules\application\models\ApplicationData;
use app\modules\industry\models\search\ApplicationDataSearch;
use app\modules\industry\traits\CommonControllerTrait;
use kilyanov\architect\controller\ApplicationController;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 *
 * @property-read array $cfgParams
 */
class ApplicationDataController extends ApplicationController
{
    use CommonControllerTrait;

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
     * @return array|string[]
     */
    public function actions(): array
    {
        $actions = parent::actions();
        return ArrayHelper::merge(
            $actions,
            [
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
}
