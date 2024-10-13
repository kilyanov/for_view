<?php

declare(strict_types=1);

namespace app\modules\rationing;

use app\modules\rationing\models\RationingDevice;
use app\modules\rationing\models\RationingDeviceData;
use app\modules\rationing\models\RationingProduct;
use app\modules\rationing\models\RationingProductData;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\db\BaseActiveRecord;

class Bootstrap implements BootstrapInterface
{
    /**
     * @param $app
     *
     * @return void
     */
    public function bootstrap($app): void
    {
        $this->setRationingDeviceData();
        $this->setRationingProductData();
    }

    /**
     * @return void
     */
    protected function setRationingDeviceData(): void
    {
        Event::on(RationingDeviceData::class, BaseActiveRecord::EVENT_AFTER_INSERT, static function (Event $event) {
            /** @var  $sender RationingDeviceData */
            $sender = $event->sender;
            /** @var RationingDevice $rationingDevice */
            $rationingDevice = RationingDevice::find()->ids($sender->rationingDeviceId)->one();
            $total = RationingDeviceData::find()
                ->andWhere(['rationingDeviceId' => $sender->rationingDeviceId])
                ->hidden()
                ->sum('normaAll');
            $rationingDevice->norma = $total;
            $rationingDevice->save();
        });
        Event::on(RationingDeviceData::class, BaseActiveRecord::EVENT_AFTER_UPDATE, static function (Event $event) {
            /** @var  $sender RationingDeviceData */
            $sender = $event->sender;
            /** @var RationingDevice $rationingDevice */
            $rationingDevice = RationingDevice::find()->ids($sender->rationingDeviceId)->one();
            $total = RationingDeviceData::find()
                ->andWhere(['rationingDeviceId' => $sender->rationingDeviceId])
                ->hidden()
                ->sum('normaAll');
            $rationingDevice->norma = $total;
            $rationingDevice->save();
        });
    }

    /**
     * @return void
     */
    protected function setRationingProductData(): void
    {
        Event::on(RationingProductData::class, BaseActiveRecord::EVENT_AFTER_INSERT, static function (Event $event) {
            /** @var  $sender RationingProductData */
            $sender = $event->sender;
            /** @var RationingProduct $rationingProduct */
            $rationingProduct = RationingProduct::find()->ids($sender->rationingId)->one();
            $total = RationingProductData::find()
                ->andWhere(['rationingId' => $sender->rationingId])
                ->hidden()
                ->sum('normaAll');
            $rationingProduct->norma = $total;
            $rationingProduct->save();
        });
    }
}
