<?php

declare(strict_types=1);

namespace app\modules\nso\behaviors;

use app\modules\nso\models\Stand;
use Yii;
use yii\base\InvalidConfigException;
use yii\behaviors\AttributeBehavior;
use yii\db\BaseActiveRecord;

class StandBehavior extends AttributeBehavior
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
        /** @var Stand $owner */
        $owner = $this->owner;
        $owner->dateVerifications = Yii::$app->formatter->asDate($owner->dateVerifications, 'php:Y-m-d');
    }

    /**
     * @param $event
     * @return void
     * @throws InvalidConfigException
     */
    public function getAttribute($event): void
    {
        /** @var Stand $owner */
        $owner = $this->owner;
        $owner->dateVerifications = Yii::$app->formatter->asDate($owner->dateVerifications, 'php:d.m.Y');
    }
}
