<?php

use app\modules\resource\models\Resource;
use kilyanov\architect\interfaces\ImportInterface;

return [
    'A' => [
        'type' => ImportInterface::TYPE_CLOSURE,
        'value' => function ($data) {
            $cfgParams = [
                'name' => $data['A'] !== null ? trim($data['A']) : null,
                'mark' => $data['B'] !== null ? trim($data['B']) : null,
                'stamp' => $data['C'] !== null ? trim($data['C']) : null,
                'size' => $data['D'] !== null ? trim($data['D']) : null,
                'ed' => $data['E'] !== null ? trim($data['E']) : null,
            ];
            $model = Resource::find()->andWhere($cfgParams)->hidden()->one();
            if ($model === null) {
                $model = new Resource($cfgParams);
                $model->save();
            }
            return $model?->id;
        },
        'attribute' => 'resourceId',
    ],
    'F' => [
        'type' => ImportInterface::TYPE_FLOAT,
        'attribute' => 'quantity',
    ],
    'J' => [
        'type' => ImportInterface::TYPE_STRING,
        'attribute' => 'comment',
    ],
];
