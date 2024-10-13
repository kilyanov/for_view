<?php

declare(strict_types=1);

namespace app\modules\product\controllers;

use app\modules\product\models\ProductNode;
use app\modules\product\models\search\ProductNodeSearch;
use kilyanov\architect\controller\ApplicationController;
use yii\web\Response;

class NodeController extends ApplicationController
{
    /**
     * @return void
     */
    public function init(): void
    {
        $this->setModelClass(ProductNode::class);
        $this->setSearchModelClass(ProductNodeSearch::class);
        parent::init();
    }

    /**
     * @param string $productId
     * @param string|null $name
     * @return Response
     */
    public function actionList(string $productId, ?string $name = null): Response
    {
        $query = ProductNode::find()
            ->select([
                'id',
                'text' => 'name',
            ])
            ->andFilterWhere(['productId' => $productId])
            ->andFilterWhere(['like', 'name', $name])
            ->hidden();

        $result['results'] = $query
            ->limit(10)
            ->asArray()
            ->all();

        return $this->asJson($result);
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
                'attribute' => 'productId',
                'value' => function ($model) {
                    /** @var ProductNode $model */
                    return $model->productRelation->getFullName();
                }
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
