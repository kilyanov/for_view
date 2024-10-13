<?php

declare(strict_types=1);

namespace app\modules\product\controllers;

use app\modules\product\models\search\ProductSearch;
use app\modules\product\models\Product;
use kilyanov\architect\controller\ApplicationController;

class DefaultController extends ApplicationController
{
    /**
     * @return void
     */
    public function init(): void
    {
        $this->setModelClass(Product::class);
        $this->setSearchModelClass(ProductSearch::class);
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
