<?php

declare(strict_types=1);

namespace app\modules\rationing\behaviors;

use app\modules\rationing\models\RationingDeviceData;
use yii\behaviors\AttributeBehavior;
use yii\db\BaseActiveRecord;

/**
 *
 * @property-write mixed $attribute
 */
class RationingDeviceDataBehavior extends AttributeBehavior
{
    /**
     * @return string[]
     */
    public function events(): array
    {
        return [
            BaseActiveRecord::EVENT_BEFORE_INSERT => 'setAttribute',
            BaseActiveRecord::EVENT_BEFORE_UPDATE => 'setAttribute',
        ];
    }

    /**
     * @param $event
     * @return void
     */
    public function setAttribute($event): void
    {
        /** @var RationingDeviceData $owner */
        $owner = $this->owner;
        if (!empty($owner->countItems) && !empty($owner->norma)) {
            $owner->normaAll = $owner->countItems * $owner->norma;
        }
    }

    /**
     * @param $event
     * @return void
     */
    public function getAttribute($event): void
    {
        /** @var RationingDeviceData $owner */
        $owner = $this->owner;
        if (!empty($owner->countItems) && !empty($owner->norma)) {
            $owner->normaAll = $owner->countItems * $owner->norma;
        }
    }

}
