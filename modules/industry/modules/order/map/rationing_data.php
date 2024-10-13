<?php

use app\modules\industry\models\OrderRationingData;
use app\modules\personal\modules\special\models\PersonalSpecial;
use app\modules\unit\models\Unit;
use kilyanov\architect\interfaces\ImportInterface;

return [
    ImportInterface::TYPE_BEFORE_IMPORT => function ($data) {
        OrderRationingData::deleteAll($data);
    },
    'A' => [
        'type' => ImportInterface::TYPE_INTEGER,
        'attribute' => 'type',
    ],
    'B' => [
        'type' => ImportInterface::TYPE_INTEGER,
        'attribute' => 'point',
    ],
    'C' => [
        'type' => ImportInterface::TYPE_CLOSURE,
        'value' => function ($data) {
            return  ($data['C'] !== null) ? (int)$data['C'] : null;
        },
        'attribute' => 'subItem',
    ],
    'D' => [
        'type' => ImportInterface::TYPE_STRING,
        'attribute' => 'name',
    ],
    'E' => [
        'type' => ImportInterface::TYPE_CLOSURE,
        'value' => function ($data) {
            $model = Unit::find()
                ->andWhere(['name' => $data['E']])
                ->parent()
                ->one();
            return $model?->id;
        },
        'attribute' => 'unitId',
    ],
    'F' => [
        'type' => ImportInterface::TYPE_STRING,
        'attribute' => 'ed',
    ],
    'G' => [
        'type' => ImportInterface::TYPE_INTEGER,
        'attribute' => 'countItems',
    ],
    'H' => [
        'type' => ImportInterface::TYPE_FLOAT,
        'attribute' => 'periodicity',
    ],
    'I' => [
        'type' => ImportInterface::TYPE_CLOSURE,
        'value' => function ($data) {
            $model = PersonalSpecial::find()
                ->andWhere(['name' => $data['I']])
                ->one();
            return $model?->id;
        },
        'attribute' => 'specialId',
    ],
    'J' => [
        'type' => ImportInterface::TYPE_INTEGER,
        'attribute' => 'category',
    ],
    'K' => [
        'type' => ImportInterface::TYPE_FLOAT,
        'attribute' => 'norma',
    ],
    'L' => [
        'type' => ImportInterface::TYPE_CLOSURE,
        'value' => function ($data) {
            return (float)$data['K'] * (int)$data['G'];
        },
        'attribute' => 'normaAll',
    ],
];
