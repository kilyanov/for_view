<?php

declare(strict_types=1);

namespace app\modules\contract\behaviors;

use app\modules\contract\models\Contract;
use Yii;
use yii\base\InvalidConfigException;
use yii\behaviors\AttributeBehavior;
use yii\db\BaseActiveRecord;

class ContractBehavior extends AttributeBehavior
{
    /**
     * @return string[]
     */
    public function events(): array
    {
        return [
            BaseActiveRecord::EVENT_BEFORE_INSERT => 'setValueAttribute',
            BaseActiveRecord::EVENT_BEFORE_UPDATE => 'setValueAttribute',
            BaseActiveRecord::EVENT_AFTER_FIND => 'getValueAttribute',
        ];
    }

    /**
     * @param $event
     * @return void
     * @throws InvalidConfigException
     */
    public function setValueAttribute($event): void
    {
        /** @var Contract $owner */
        $owner = $this->owner;
        $owner->dateFinding = Yii::$app->formatter->asDate($owner->dateFinding, 'php:Y-m-d');
        $owner->validityPeriod = Yii::$app->formatter->asDate($owner->validityPeriod, 'php:Y-m-d');
    }

    /**
     * @param $event
     * @return void
     * @throws InvalidConfigException
     */
    public function getValueAttribute($event): void
    {
        /** @var Contract $owner */
        $owner = $this->owner;
        $owner->dateFinding = Yii::$app->formatter->asDate($owner->dateFinding, 'php:d.m.Y');
        $owner->validityPeriod = Yii::$app->formatter->asDate($owner->validityPeriod, 'php:d.m.Y');
    }

}
