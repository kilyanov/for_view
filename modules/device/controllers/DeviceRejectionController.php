<?php

declare(strict_types=1);

namespace app\modules\device\controllers;

use app\common\rbac\CollectionRolls;
use app\modules\device\models\DeviceRejection;
use app\modules\device\models\search\DeviceRejectionSearch;
use app\modules\device\trait\CommonControllerTrait;
use kilyanov\architect\controller\ApplicationController;
use kilyanov\architect\interfaces\AnswerInterface;

/**
 * DeviceRejection controller for the `reference` module
 */
class DeviceRejectionController extends ApplicationController
{
    use CommonControllerTrait;

    /**
     * @return void
     */
    public function init(): void
    {
        $this->setModelClass(DeviceRejection::class);
        $this->setSearchModelClass(DeviceRejectionSearch::class);
        parent::init();
        $this->setForceReload('rejection-' . AnswerInterface::MODAL_ID);
        $this->setTypeRender(ApplicationController::TYPE_RENDER_PARTIAL);
        $this->setListAccess([CollectionRolls::ROLE_VERIFIER], true);
    }
}
