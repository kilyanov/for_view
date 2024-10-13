<?php

declare(strict_types=1);

namespace app\modules\product\controllers;

use app\modules\product\models\ProductBlock;
use app\modules\product\models\search\ProductBlockSearch;
use kilyanov\architect\controller\ApplicationController;
use yii\web\Response;

class BlockController extends ApplicationController
{
    /**
     * @return void
     */
    public function init(): void
    {
        $this->setModelClass(ProductBlock::class);
        $this->setSearchModelClass(ProductBlockSearch::class);
        parent::init();
    }

    /**
     * @param string $productId
     * @param string|null $productNodeId
     * @param string|null $name
     * @return Response
     */
    public function actionList(string $productId, ?string $productNodeId, ?string $name = null): Response
    {
        $query = ProductBlock::find()
            ->andFilterWhere([ProductBlock::tableName() . '.[[productId]]' => $productId])
            ->andFilterWhere([ProductBlock::tableName() . '.[[productNodeId]]' => $productNodeId])
            ->andFilterWhere(['like', ProductBlock::tableName() . '.[[name]]', $name])
            ->hidden();

        $models = $query->limit(10)->all();

        $result['results'] = array_map(function(ProductBlock $block){
            return ['id' => $block->id, 'text' => $block->getFullName()];
        }, $models);

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
                    /** @var ProductBlock $model */
                    return $model->productRelation ? $model->productRelation->getFullName() : '';
                }
            ],
            [
                'attribute' => 'productNodeId',
                'value' => function ($model) {
                    /** @var ProductBlock $model */
                    return $model->productNodeRelation ? $model->productNodeRelation->getFullName() : '';
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
