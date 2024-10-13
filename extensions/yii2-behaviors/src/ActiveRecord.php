<?php

declare(strict_types=1);

namespace kilyanov\behaviors;

use kilyanov\behaviors\common\IdAttributeBehavior;
use kilyanov\behaviors\common\TimestampBehavior;
use yii\db\ActiveRecord as ActiveRecordAlias;

class ActiveRecord extends ActiveRecordAlias
{
    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            'TimestampBehavior' => [
                'class' => TimestampBehavior::class,
            ],
            'IdAttributeBehavior' => [
                'class' => IdAttributeBehavior::class,
            ],
        ];
    }
}
