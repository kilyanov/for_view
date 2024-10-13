<?php

use app\modules\resource\models\Resource;
use kilyanov\architect\interfaces\ImportInterface;

return [
    'A' => [
        'type' => ImportInterface::TYPE_CLOSURE,
        'value' => function ($data) {
            $model = Resource::find()->andWhere([
                'name' => $data['A'] !== null ? trim($data['A']) : null,
                'mark' => $data['B'] !== null ? trim($data['B']) : null,
            ])->hidden()->one();
            if ($model === null) {
                $model = new Resource([
                    'name' => $data['A'] !== null ? trim($data['A']) : null,
                    'mark' => $data['B'] !== null ? trim($data['B']) : null,
                    'ed' => $data['G'] !== null ? trim($data['G']) : null,
                ]);
                $model->save();
            }
            return $model?->id;
        },
        'attribute' => 'resourceId',
    ],
    'C' => [
        'type' => ImportInterface::TYPE_FLOAT,
        'attribute' => 'quantity',
    ],
    'J' => [
        'type' => ImportInterface::TYPE_STRING,
        'attribute' => 'comment',
    ],
];
