<?php

declare(strict_types=1);

namespace app\modules\industry\modules\order\controllers;

use app\common\rbac\CollectionRolls;
use app\modules\industry\models\OrderToUnit;
use app\modules\industry\models\search\OrderToUnitSearch;
use app\modules\industry\traits\CommonControllerTrait;
use kilyanov\architect\controller\ApplicationController;

class UnitController extends ApplicationController
{
    use CommonControllerTrait;

    /**
     * @return void
     */
    public function init(): void
    {
        $this->setModelClass(OrderToUnit::class);
        $this->setSearchModelClass(OrderToUnitSearch::class);
        parent::init();
        $this->setListAccess([CollectionRolls::ROLE_SECONDARY_CIL]);
        $this->layout = '/main-fluid';
    }
}
