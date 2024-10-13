<?php

declare(strict_types=1);

namespace app\modules\device\modules\group\controllers;

use app\common\rbac\CollectionRolls;
use app\modules\device\models\DeviceGroup;
use app\modules\device\models\search\DeviceGroupSearch;
use kilyanov\architect\controller\ApplicationController;

/**
 * Default controller for the `group` module
 */
class DefaultController extends ApplicationController
{
    /**
     * @return void
     */
    public function init(): void
    {
        $this->setModelClass(DeviceGroup::class);
        $this->setSearchModelClass(DeviceGroupSearch::class);
        parent::init();
        $this->setListAccess([CollectionRolls::ROLE_VERIFIER]);
    }
}
