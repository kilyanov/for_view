<?php

declare(strict_types=1);

namespace app\modules\unit\controllers;

use app\modules\unit\models\search\UnitSearch;
use app\modules\unit\models\Unit;
use kilyanov\architect\controller\ApplicationController;

class DefaultController extends ApplicationController
{
    /**
     * @return void
     */
    public function init(): void
    {
        $this->setModelClass(Unit::class);
        $this->setSearchModelClass(UnitSearch::class);
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
                'attribute' => 'parentId',
                'value' => function ($model) {
                    return $model->parentRelation ?
                        $model->parentRelation->getFullName() : '';
                }
            ],
            [
                'attribute' => 'name',
            ],
        ];
    }
}
