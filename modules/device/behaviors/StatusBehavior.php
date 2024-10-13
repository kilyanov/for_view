<?php

declare(strict_types=1);

namespace app\modules\device\behaviors;

use app\modules\device\interface\StatusAttributeInterface;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;

class StatusBehavior extends AttributeBehavior
{

    /**
     * @var string
     */
    public string $searchAttr = 'deviceId';

    /**
     * @return string[]
     */
    public function events(): array
    {
        return [
            BaseActiveRecord::EVENT_BEFORE_INSERT => 'setStatus',
            BaseActiveRecord::EVENT_BEFORE_UPDATE => 'setStatusUpdate',
        ];
    }

    /**
     * @return void
     */
    public function setStatus(): void
    {
        /** @var ActiveRecord $owner */
        $owner = $this->owner;
        $class = $owner::class;
        $class::updateAll(
            ['status' => StatusAttributeInterface::STATUS_BLOCK],
            [$this->searchAttr => $owner->{$this->searchAttr}]
        );
    }

    /**
     * @return void
     */
    public function setStatusUpdate(): void
    {
        /** @var ActiveRecord $owner */
        $owner = $this->owner;
        $class = $owner::class;

        if ($owner->status === StatusAttributeInterface::STATUS_ACTIVE) {
            $class::updateAll(
                ['status' => StatusAttributeInterface::STATUS_BLOCK],
                [
                    'and',
                    [$this->searchAttr => $owner->{$this->searchAttr}],
                    ['!=', 'id', $owner->id]
                ]
            );
        }
    }
}
