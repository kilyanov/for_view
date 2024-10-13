<?php

declare(strict_types=1);

namespace app\modules\application\behaviors;

use app\modules\application\models\ApplicationData;
use Yii;
use yii\base\InvalidConfigException;
use yii\behaviors\AttributeBehavior;
use yii\db\BaseActiveRecord;

/**
 *
 * @property-write mixed $attribute
 * @property-read void $percent
 * @property-read void $status
 */
class ApplicationDataBehavior extends AttributeBehavior
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
        /** @var ApplicationData $owner */
        $owner = $this->owner;
        $owner->deliveryTime = !empty($owner->deliveryTime) ?
            Yii::$app->formatter->asDate($owner->deliveryTime, 'php:Y-m-d') : null;
        $owner->receiptDate = !empty($owner->receiptDate) ?
            Yii::$app->formatter->asDate($owner->receiptDate, 'php:Y-m-d') : null;
    }

    /**
     * @param $event
     * @return void
     * @throws InvalidConfigException
     */
    public function getAttribute($event): void
    {
        /** @var ApplicationData $owner */
        $owner = $this->owner;
        $owner->deliveryTime = !empty($owner->deliveryTime) ?
            Yii::$app->formatter->asDate($owner->deliveryTime, 'php:d.m.Y') : null;
        $owner->receiptDate = !empty($owner->receiptDate) ?
            Yii::$app->formatter->asDate($owner->receiptDate, 'php:d.m.Y') : null;
        $this->getPercent();
        $this->getStatus();
    }

    /**
     * @return void
     */
    private function getStatus(): void
    {
        /** @var ApplicationData $owner */
        $owner = $this->owner;
        $result = ($owner->quantityReceipt - $owner->quantity);
        if ($result == 0) {
            $owner->status = 'Получено';
        }
        elseif ($result > 0) {
            $owner->status = 'Получено не в полном объёме';
        }
        else {
            $owner->status = 'Не получено';
        }
    }

    /**
     * @return void
     */
    private function getPercent(): void
    {
        /** @var ApplicationData $owner */
        $owner = $this->owner;
        $owner->percent = $owner->quantity == 0 ? 0.00 : (float)($owner->quantityReceipt / $owner->quantity * 100);
    }
}
