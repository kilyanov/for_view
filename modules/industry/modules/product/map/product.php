<?php

use app\modules\industry\models\RepairProduct;
use app\modules\product\models\Product;
use app\modules\product\models\ProductBlock;
use app\modules\product\models\ProductNode;
use kilyanov\architect\interfaces\ImportInterface;

return [
    ImportInterface::TYPE_EXIST => function ($data) {
        $data = RepairProduct::find()->andWhere([
            'productId' => $data['productId'],
            'productNodeId' => $data['productNodeId'],
            'productBlockId' => $data['productBlockId'],
            'number' => $data['number'],
        ])->one();
        return $data?->id;
    },
    'A' => [
        'type' => ImportInterface::TYPE_CLOSURE,
        'value' => function ($data) {
            $model = Product::find()->andWhere(['name' => $data['A']])->one();
            return $model?->id;
        },
        'attribute' => 'productId',
    ],
    'B' => [
        'type' => ImportInterface::TYPE_CLOSURE,
        'value' => function ($data) {
            $model = ProductNode::find()
                ->joinWith(['productRelation'])
                ->andWhere([ProductNode::tableName() . '.[[name]]' => $data['B']])
                ->andWhere([Product::tableName() . '.[[name]]' => $data['A']])
                ->one();
            return $model?->id;
        },
        'attribute' => 'productNodeId',
    ],
    'C' => [
        'type' => ImportInterface::TYPE_CLOSURE,
        'value' => function ($data) {
            $model = ProductBlock::find()
                ->joinWith(['productRelation', 'productNodeRelation'])
                ->andWhere([ProductBlock::tableName() . '.[[name]]' => $data['C']])
                ->andWhere([ProductNode::tableName() . '.[[name]]' => $data['B']])
                ->andWhere([Product::tableName() . '.[[name]]' => $data['A']])
                ->one();
            return $model?->id;
        },
        'attribute' => 'productBlockId',
    ],
    'D' => [
        'type' => ImportInterface::TYPE_STRING,
        'attribute' => 'number',
    ],
    'E' => [
        'type' => ImportInterface::TYPE_STRING,
        'attribute' => 'comment',
    ],
    'F' => [
        'type' => ImportInterface::TYPE_INTEGER,
        'attribute' => 'hidden',
    ],
];
