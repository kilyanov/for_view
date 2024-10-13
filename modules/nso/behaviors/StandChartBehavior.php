<?php

declare(strict_types=1);

namespace app\modules\nso\behaviors;

use app\modules\nso\models\StandChart;
use Yii;
use yii\base\InvalidConfigException;
use yii\behaviors\AttributeBehavior;
use yii\db\BaseActiveRecord;

class StandChartBehavior extends AttributeBehavior
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
        /** @var StandChart $owner */
        $owner = $this->owner;
        if ($owner->dateFact !== null) {
            $owner->dateFact = Yii::$app->formatter->asDate($owner->dateFact, 'php:Y-m-d');
            $owner->monthFact = (int)Yii::$app->formatter->asDate($owner->dateFact, 'php:m');
        }
        else {
            $owner->dateFact = null;
            $owner->monthFact = null;
        }
    }

    /**
     * @param $event
     * @return void
     * @throws InvalidConfigException
     */
    public function getAttribute($event): void
    {
        /** @var StandChart $owner */
        $owner = $this->owner;
        if ($owner->dateFact !== null) {
            $owner->dateFact = Yii::$app->formatter->asDate($owner->dateFact, 'php:d.m.Y');
            $owner->monthFact = (int)Yii::$app->formatter->asDate($owner->dateFact, 'php:m');
        }
        else {
            $owner->dateFact = null;
            $owner->monthFact = null;
        }
    }
}
