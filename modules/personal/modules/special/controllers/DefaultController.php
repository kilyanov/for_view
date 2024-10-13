<?php

declare(strict_types=1);

namespace app\modules\personal\modules\special\controllers;

use app\modules\personal\modules\special\models\PersonalSpecial;
use app\modules\personal\modules\special\models\search\PersonalSpecialSearch;
use kilyanov\architect\controller\ApplicationController;

class DefaultController extends ApplicationController
{
    /**
     * @return void
     */
    public function init(): void
    {
        $this->setModelClass(PersonalSpecial::class);
        $this->setSearchModelClass(PersonalSpecialSearch::class);
        parent::init();
    }
}
