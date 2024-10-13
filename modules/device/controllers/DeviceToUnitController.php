<?php

declare(strict_types=1);

namespace app\modules\device\controllers;

use app\common\rbac\CollectionRolls;
use app\modules\device\models\DeviceToUnit;
use app\modules\device\models\search\DeviceToUnitSearch;
use app\modules\device\trait\CommonControllerTrait;
use kilyanov\architect\controller\ApplicationController;
use kilyanov\architect\interfaces\AnswerInterface;

/**
 * DeviceToUnit controller for the `reference` module
 */
class DeviceToUnitController extends ApplicationController
{
    use CommonControllerTrait;

    /**
     * @return void
     */
    public function init(): void
    {
        $this->setModelClass(DeviceToUnit::class);
        $this->setSearchModelClass(DeviceToUnitSearch::class);
        parent::init();
        $this->setForceReload('unit-' . AnswerInterface::MODAL_ID);
        $this->setTypeRender(ApplicationController::TYPE_RENDER_PARTIAL);
        $this->setListAccess([CollectionRolls::ROLE_VERIFIER], true);
    }
}
