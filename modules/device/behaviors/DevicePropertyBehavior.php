<?php

declare(strict_types=1);

namespace app\modules\device\behaviors;

use app\modules\device\models\DeviceProperty;
use yii\behaviors\AttributeBehavior;
use yii\db\BaseActiveRecord;

class DevicePropertyBehavior extends AttributeBehavior
{
    /**
     * @return string[]
     */
    public function events(): array
    {
        return [
            BaseActiveRecord::EVENT_AFTER_FIND => 'getValueAttribute',
        ];
    }

    /**
     * @param $event
     * @return void
     */
    public function getValueAttribute($event): void
    {
        /** @var DeviceProperty $owner */
        $owner = $this->owner;
        $owner->deviceTypeId = $owner->deviceNameRelation?->deviceTypeId;
    }

}
