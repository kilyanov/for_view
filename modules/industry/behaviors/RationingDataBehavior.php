<?php

declare(strict_types=1);

namespace app\modules\industry\behaviors;

use app\modules\industry\models\OrderRationingDataClose;
use app\modules\rationing\models\RationingProduct;
use app\modules\rationing\models\RationingProductData;
use Exception;
use yii\behaviors\AttributeBehavior;
use yii\db\BaseActiveRecord;

/**
 *
 * @property-write mixed $attribute
 */
class RationingDataBehavior extends AttributeBehavior
{

    /**
     * @return string[]
     */
    public function events(): array
    {
        return [
            BaseActiveRecord::EVENT_AFTER_UPDATE => 'setAttribute',
        ];
    }

    /**
     * @param $event
     * @return void
     * @throws Exception
     */
    public function setAttribute($event): void
    {
        $sender = $event->sender;
        /** @var RationingProduct $rationingProduct */
        $rationingProduct = RationingProduct::find()->ids($sender->rationingId)->one();
        $total = RationingProductData::find()
            ->andWhere(['rationingId' => $sender->rationingId])
            ->hidden()
            ->sum('normaAll');
        $rationingProduct->norma = $total;
        $rationingProduct->save(false);
    }
}
