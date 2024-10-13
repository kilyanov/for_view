<?php

declare(strict_types=1);

namespace app\modules\industry\behaviors;

use app\common\interface\HiddenAttributeInterface;
use app\modules\industry\models\OrderRationing;
use app\modules\industry\models\OrderRationingData;
use app\modules\rationing\models\RationingProductData;
use Exception;
use yii\behaviors\AttributeBehavior;
use yii\db\BaseActiveRecord;
use yii\helpers\ArrayHelper;

/**
 *
 * @property-write mixed $attribute
 */
class OrderRationingDataBehavior extends AttributeBehavior
{

    /**
     * @return string[]
     */
    public function events(): array
    {
        return [
            BaseActiveRecord::EVENT_AFTER_INSERT => 'after',
            BaseActiveRecord::EVENT_AFTER_UPDATE => 'after',
        ];
    }

    /**
     * @param $event
     * @return void
     * @throws Exception
     */
    public function after($event): void
    {
        $sender = $event->sender;
        /** @var OrderRationing $rationingProduct */
        $rationingProduct = OrderRationing::find()->ids($sender->rationingId)->one();
        $total = OrderRationingData::find()
            ->andWhere(['rationingId' => $sender->rationingId])
            ->hidden()
            ->sum('normaAll');
        $rationingProduct->norma = $total === null ? 0.00 : $total;
        $rationingProduct->save(false);
    }
}
