<?php

declare(strict_types=1);

namespace app\modules\personal\controllers;

use app\common\grid\PersonalGroupColumn;
use app\common\grid\PersonalSpecialColumn;
use app\common\grid\StatusColumn;
use app\common\grid\UnitColumn;
use app\modules\personal\models\Personal;
use app\modules\personal\models\search\PersonalSearch;
use kilyanov\architect\controller\ApplicationController;

class DefaultController extends ApplicationController
{
    /**
     * @return void
     */
    public function init(): void
    {
        $this->layout = '/main-fluid';
        $this->setModelClass(Personal::class);
        $this->setSearchModelClass(PersonalSearch::class);
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
                'attribute' => 'type',
                'value' => function ($model) {
                    /** @var Personal $model */
                    return $model->getType();
                }
            ],
            [
                'attribute' => 'unitId',
                'value' => function ($model) {
                    /** @var Personal $model */
                    return $model->unitRelation->getFullName();
                }
            ],
            [
                'attribute' => 'groupId',
                'value' => function ($model) {
                    /** @var Personal $model */
                    return $model->groupRelation ? $model->groupRelation->name : '';
                }
            ],
            [
                'attribute' => 'specialId',
                'value' => function ($model) {
                    /** @var Personal $model */
                    return $model->specialRelation->name;
                }
            ],
            ['attribute' => 'discharge'],
            ['attribute' => 'fistName'],
            ['attribute' => 'lastName'],
            ['attribute' => 'secondName'],
            [
                'format' => ['decimal', 2],
                'attribute' => 'salary'
            ],
            ['attribute' => 'ratio'],
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    /** @var Personal $model */
                    return $model->getStatus();
                }
            ],
            ['attribute' => 'description'],
        ];
    }
}
