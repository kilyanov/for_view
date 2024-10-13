<?php

declare(strict_types=1);

namespace app\modules\industry\modules\machine\controllers;

use app\modules\industry\models\Machine;
use app\modules\industry\models\search\MachineSearch;
use kilyanov\architect\controller\ApplicationController;

/**
 * Default controller for the `machine` module
 */
class DefaultController extends ApplicationController
{
    /**
     * @return void
     */
    public function init(): void
    {
        $this->setModelClass(Machine::class);
        $this->setSearchModelClass(MachineSearch::class);
        parent::init();
    }
}
