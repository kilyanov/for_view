<?php

declare(strict_types=1);

namespace app\modules\contract\controllers;

use app\modules\contract\models\Contract;
use app\modules\contract\models\search\ContractSearch;
use kilyanov\architect\controller\ApplicationController;

class DefaultController extends ApplicationController
{
    public function init(): void
    {
        $this->layout = '/main-fluid';
        $this->setModelClass(Contract::class);
        $this->setSearchModelClass(ContractSearch::class);
        parent::init();
    }
}
