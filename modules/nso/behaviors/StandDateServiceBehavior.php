<?php

declare(strict_types=1);

namespace app\modules\nso\behaviors;

use app\modules\nso\models\StandDateService;
use Yii;
use yii\base\InvalidConfigException;
use yii\behaviors\AttributeBehavior;
use yii\db\BaseActiveRecord;

class StandDateServiceBehavior extends AttributeBehavior
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
        /** @var StandDateService $owner */
        $owner = $this->owner;
        $owner->dateService = Yii::$app->formatter->asDate($owner->dateService, 'php:Y-m-d');
    }

    /**
     * @param $event
     * @return void
     * @throws InvalidConfigException
     */
    public function getAttribute($event): void
    {
        /** @var StandDateService $owner */
        $owner = $this->owner;
        $owner->dateService = Yii::$app->formatter->asDate($owner->dateService, 'php:d.m.Y');
    }
}
