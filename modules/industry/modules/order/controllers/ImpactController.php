<?php

declare(strict_types=1);

namespace app\modules\industry\modules\order\controllers;

use app\common\rbac\CollectionRolls;
use app\modules\industry\models\OrderToImpact;
use app\modules\industry\models\search\OrderToImpactSearch;
use app\modules\industry\traits\CommonControllerTrait;
use kilyanov\architect\controller\ApplicationController;

/**
 * OrderImpact controller for the `reference` module
 */
class ImpactController extends ApplicationController
{
    use CommonControllerTrait;

    /**
     * @return void
     */
    public function init(): void
    {
        $this->setModelClass(OrderToImpact::class);
        $this->setSearchModelClass(OrderToImpactSearch::class);
        parent::init();
        $this->setListAccess([CollectionRolls::ROLE_SECONDARY_CIL]);
        $this->layout = '/main-fluid';
    }
}
