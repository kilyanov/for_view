<?php

declare(strict_types=1);

namespace app\modules\impact\controllers;

use app\modules\impact\models\Impact;
use app\modules\impact\models\search\ImpactSearch;
use kilyanov\architect\controller\ApplicationController;

class DefaultController extends ApplicationController
{
    /**
     * @return void
     */
    public function init(): void
    {
        $this->setModelClass(Impact::class);
        $this->setSearchModelClass(ImpactSearch::class);
        parent::init();
    }

    /**
     * @return array
     */
    public function getExportAttribute(): array
    {
        return [
            [
                'attribute' => 'id',
            ],
            [
                'attribute' => 'mark',
            ],
            [
                'attribute' => 'name',
            ],
            [
                'attribute' => 'description',
            ],
        ];
    }
}
