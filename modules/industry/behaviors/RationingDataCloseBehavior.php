<?php

declare(strict_types=1);

namespace app\modules\industry\behaviors;

use app\modules\industry\models\OrderRationingDataClose;
use Exception;
use yii\behaviors\AttributeBehavior;
use yii\db\BaseActiveRecord;

class RationingDataCloseBehavior extends AttributeBehavior
{

    /**
     * @return string[]
     */
    public function events(): array
    {
        return [
            BaseActiveRecord::EVENT_AFTER_FIND => 'setAttributeFind',
        ];
    }

    /**
     * @param $event
     * @return void
     * @throws Exception
     */
    public function setAttributeFind($event): void
    {
        /** @var OrderRationingDataClose $owner */
        $owner = $this->owner;
        $owner->order = $owner->orderRationingDataRelation ?
            $owner->orderRationingDataRelation->rationingRelation->orderRelation->getFullName() : null;
    }
}
