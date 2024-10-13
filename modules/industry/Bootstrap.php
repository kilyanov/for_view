<?php

declare(strict_types=1);

namespace app\modules\industry;

use app\common\interface\HiddenAttributeInterface;
use app\modules\industry\models\OrderRationing;
use app\modules\industry\models\OrderRationingData;
use app\modules\industry\models\PresentationBookDataDeviceRepair;
use app\modules\industry\models\PresentationBookDataProduct;
use app\modules\rationing\models\RationingProductData;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\db\BaseActiveRecord;
use yii\helpers\ArrayHelper;

class Bootstrap implements BootstrapInterface
{
    /**
     * @param $app
     *
     * @return void
     */
    public function bootstrap($app): void
    {
        $this->setPresentationBookDataProduct();
        $this->setPresentationBookDataDeviceRepair();
        $this->setOrderRationingData();
    }

    /**
     * @return void
     */
    protected function setPresentationBookDataProduct(): void
    {
        Event::on(PresentationBookDataProduct::class, BaseActiveRecord::EVENT_AFTER_UPDATE, static function (Event $event) {
            /** @var  $sender PresentationBookDataProduct */
            $sender = $event->sender;
            $total = PresentationBookDataProduct::find()
                ->andWhere(['bookId' => $sender->bookId])
                ->sum('norma');
            $sender->bookRelation->norma = $total;
            $sender->bookRelation->save();
        });
        Event::on(PresentationBookDataProduct::class, BaseActiveRecord::EVENT_AFTER_DELETE, static function (Event $event) {
            /** @var  $sender PresentationBookDataProduct */
            $sender = $event->sender;
            $total = PresentationBookDataProduct::find()
                ->andWhere(['bookId' => $sender->bookId])
                ->sum('norma');
            $sender->bookRelation->norma = $total;
            $sender->bookRelation->save();
        });
    }

    /**
     * @return void
     */
    protected function setPresentationBookDataDeviceRepair(): void
    {
        Event::on(PresentationBookDataDeviceRepair::class, BaseActiveRecord::EVENT_AFTER_INSERT, static function (Event $event) {
            /** @var  $sender PresentationBookDataDeviceRepair */
            $sender = $event->sender;
            $total = PresentationBookDataDeviceRepair::find()
                ->andWhere(['bookId' => $sender->bookId])
                ->sum('norma');
            $sender->bookRelation->norma = $total;
            $sender->bookRelation->save();
        });
        Event::on(PresentationBookDataDeviceRepair::class, BaseActiveRecord::EVENT_AFTER_UPDATE, static function (Event $event) {
            /** @var  $sender PresentationBookDataDeviceRepair */
            $sender = $event->sender;
            $total = PresentationBookDataDeviceRepair::find()
                ->andWhere(['bookId' => $sender->bookId])
                ->sum('norma');
            $sender->bookRelation->norma = $total;
            $sender->bookRelation->save();
        });
        Event::on(PresentationBookDataDeviceRepair::class, BaseActiveRecord::EVENT_AFTER_DELETE, static function (Event $event) {
            /** @var  $sender PresentationBookDataDeviceRepair */
            $sender = $event->sender;
            $total = PresentationBookDataDeviceRepair::find()
                ->andWhere(['bookId' => $sender->bookId])
                ->sum('norma');
            $sender->bookRelation->norma = $total;
            $sender->bookRelation->save();
        });
    }

    /**
     * @return void
     */
    protected function setOrderRationingData(): void
    {
        Event::on(OrderRationing::class, BaseActiveRecord::EVENT_AFTER_INSERT, static function (Event $event) {
            /** @var  $sender OrderRationing */
            $sender = $event->sender;
            $rationingData = RationingProductData::find()
                ->andWhere(['rationingId' => $sender->rationingId])
                ->andWhere([RationingProductData::tableName() . '.[[hidden]]' => HiddenAttributeInterface::HIDDEN_NO])
                ->order()
                ->all();
            $total = 0.00;
            if (count($rationingData) > 0) {
                foreach ($rationingData as $data) {
                    /** @var RationingProductData $data */
                    $addAttribute = $data->getAttributes([
                        'type', 'point', 'subItem',
                        'name', 'machineId', 'unitId', 'ed',
                        'countItems', 'periodicity',
                        'category', 'norma', 'normaAll',
                        'specialId', 'comment',
                        'sort', 'checkList', 'hidden'
                    ]);
                    if (!empty($owner->countItems) && !empty($owner->norma)) {
                        $total += $data->countItems * $data->norma;
                    }
                    $orderRationingData = new OrderRationingData(
                        ArrayHelper::merge($addAttribute, ['rationingId' => $sender->id])
                    );
                    $orderRationingData->save();
                }
            }
            $sender->norma = $total;
            $sender->save();
        });
        Event::on(OrderRationingData::class, BaseActiveRecord::EVENT_AFTER_UPDATE, static function (Event $event) {
            /** @var $sender OrderRationingData */
            $sender = $event->sender;
            $rationingData = OrderRationingData::find()
                ->select('SUM(`normaAll`)')
                ->andWhere([OrderRationingData::tableName() . '.[[rationingId]]' => $sender->rationingId])
                ->andWhere([OrderRationingData::tableName() . '.[[hidden]]' => HiddenAttributeInterface::HIDDEN_NO])
                ->scalar();
            $sender->rationingRelation->norma = $rationingData;
            $sender->rationingRelation->save();
        });
    }
}
