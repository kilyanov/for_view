<?php

declare(strict_types=1);

namespace app\modules\nso\controllers;

use app\common\rbac\CollectionRolls;
use app\modules\nso\models\search\StandChartSearch;
use app\modules\nso\models\StandChart;
use kilyanov\architect\controller\ApplicationController;

class ChartController extends ApplicationController
{
    /**
     * @return void
     */
    public function init(): void
    {
        $this->setModelClass(StandChart::class);
        $this->setSearchModelClass(StandChartSearch::class);
        parent::init();
        $this->setListAccess([CollectionRolls::ROLE_SECONDARY_CIL]);
        $this->layout = '/main-fluid';
    }
}
