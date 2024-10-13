<?php

declare(strict_types=1);

namespace app\modules\application\behaviors;

use app\modules\application\models\Application;
use app\modules\application\models\ApplicationData;
use Yii;
use yii\base\InvalidConfigException;
use yii\behaviors\AttributeBehavior;
use yii\db\BaseActiveRecord;

class ApplicationBehavior extends AttributeBehavior
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
        /** @var Application $owner */
        $owner = $this->owner;
        $owner->dateFiling = Yii::$app->formatter->asDate($owner->dateFiling, 'php:Y-m-d');
    }

    /**
     * @param $event
     * @return void
     * @throws InvalidConfigException
     */
    public function getAttribute($event): void
    {
        /** @var Application $owner */
        $owner = $this->owner;
        $owner->dateFiling = Yii::$app->formatter->asDate($owner->dateFiling, 'php:d.m.Y');
    }
}
