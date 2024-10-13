<?php

declare(strict_types=1);

namespace app\modules\device\controllers;

use app\common\rbac\CollectionRolls;
use app\modules\device\models\DeviceStandard;
use app\modules\device\models\search\DeviceStandardSearch;
use app\modules\device\trait\CommonControllerTrait;
use kilyanov\architect\controller\ApplicationController;

/**
 * DeviceStandard controller for the `reference` module
 */
class DeviceStandardController extends ApplicationController
{
    use CommonControllerTrait;

    /**
     * @return void
     */
    public function init(): void
    {
        $this->setModelClass(DeviceStandard::class);
        $this->setSearchModelClass(DeviceStandardSearch::class);
        parent::init();
        $this->setTypeRender(ApplicationController::TYPE_RENDER_PARTIAL);
        $this->setListAccess([CollectionRolls::ROLE_VERIFIER], true);
    }

    /**
     * @return array
     */
    public function actions(): array
    {
        $actions = parent::actions();
        unset($actions['index'], $actions['delete-all']);

        return $actions;
    }
}
