<?php

declare(strict_types=1);

namespace app\modules\device\controllers;

use app\common\rbac\CollectionRolls;
use app\modules\device\models\DeviceToImpact;
use app\modules\device\models\search\DeviceToImpactSearch;
use app\modules\device\trait\CommonControllerTrait;
use kilyanov\architect\controller\ApplicationController;
use kilyanov\architect\interfaces\AnswerInterface;

/**
 * DeviceToImpact controller for the `reference` module
 */
class DeviceToImpactController extends ApplicationController
{
    use CommonControllerTrait;

    /**
     * @return void
     */
    public function init(): void
    {
        $this->setModelClass(DeviceToImpact::class);
        $this->setSearchModelClass(DeviceToImpactSearch::class);
        parent::init();
        $this->setForceReload('impact-' . AnswerInterface::MODAL_ID);
        $this->setTypeRender(ApplicationController::TYPE_RENDER_PARTIAL);
        $this->setListAccess([CollectionRolls::ROLE_VERIFIER], true);
    }
}
