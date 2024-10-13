<?php

declare(strict_types=1);

namespace app\modules\device\behaviors;

use app\modules\device\models\DeviceRejection;
use Yii;
use yii\base\InvalidConfigException;
use yii\behaviors\AttributeBehavior;
use yii\db\BaseActiveRecord;

/**
 *
 * @property-write mixed $attribute
 */
class DeviceRejectionBehavior extends AttributeBehavior
{

    /**
     * @return string[]
     */
    public function events(): array
    {
        return [
            BaseActiveRecord::EVENT_BEFORE_INSERT => 'setAttribute',
            BaseActiveRecord::EVENT_BEFORE_UPDATE => 'setAttribute',
            BaseActiveRecord::EVENT_AFTER_FIND => 'getAttribute',
        ];
    }

    /**
     * @param $event
     * @return void
     * @throws InvalidConfigException
     */
    public function setAttribute($event): void
    {
        /** @var DeviceRejection $owner */
        $owner = $this->owner;
        $owner->rejection_date = Yii::$app->formatter->asDate($owner->rejection_date, 'php:Y-m-d');
    }

    /**
     * @param $event
     * @return void
     * @throws InvalidConfigException
     */
    public function getAttribute($event): void
    {
        /** @var DeviceRejection $owner */
        $owner = $this->owner;
        $owner->rejection_date = Yii::$app->formatter->asDate($owner->rejection_date, 'php:d.m.Y');
    }
}
