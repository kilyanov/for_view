<?php

declare(strict_types=1);

namespace app\modules\device\controllers;

use app\common\rbac\CollectionRolls;
use app\modules\device\models\DeviceVerification;
use app\modules\device\models\search\DeviceVerificationSearch;
use app\modules\device\trait\CommonControllerTrait;
use kilyanov\architect\controller\ApplicationController;
use kilyanov\architect\interfaces\AnswerInterface;

/**
 * DeviceVerification controller for the `reference` module
 */
class DeviceVerificationController extends ApplicationController
{
    use CommonControllerTrait;

    /**
     * @return void
     */
    public function init(): void
    {
        $this->setModelClass(DeviceVerification::class);
        $this->setSearchModelClass(DeviceVerificationSearch::class);
        parent::init();
        $this->setForceReload('verification-' . AnswerInterface::MODAL_ID);
        $this->setTypeRender(ApplicationController::TYPE_RENDER_PARTIAL);
        $this->setListAccess([CollectionRolls::ROLE_VERIFIER], true);
    }
}
