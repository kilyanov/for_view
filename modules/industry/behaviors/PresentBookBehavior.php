<?php

declare(strict_types=1);

namespace app\modules\industry\behaviors;

use app\modules\device\models\Device;
use app\modules\industry\entity\DeviceEntity;
use app\modules\industry\entity\DeviceRepairEntity;
use app\modules\industry\entity\ProductEntity;
use app\modules\industry\entity\StandEntity;
use app\modules\industry\models\OrderList;
use app\modules\industry\models\OrderRationing;
use app\modules\industry\models\OrderRationingData;
use app\modules\industry\models\PresentationBook;
use app\modules\industry\models\PresentationBookDataProduct;
use app\modules\nso\models\Stand;
use Exception;
use yii\base\Event;
use yii\base\InvalidConfigException;
use yii\behaviors\AttributeBehavior;
use yii\db\BaseActiveRecord;
use Yii;

class PresentBookBehavior extends AttributeBehavior
{
    /**
     * @return array
     */
    public function events(): array
    {
        return [
            BaseActiveRecord::EVENT_BEFORE_INSERT => 'beforeInsert',
            BaseActiveRecord::EVENT_AFTER_INSERT => 'afterInsert',
            BaseActiveRecord::EVENT_BEFORE_UPDATE => 'beforeUpdate',
            BaseActiveRecord::EVENT_AFTER_FIND => 'afterFind',
        ];
    }

    /**
     * @param Event $event
     * @return void
     * @throws InvalidConfigException
     */
    public function beforeInsert(Event $event): void
    {
        /** @var PresentationBook $model */
        $model = $event->sender;
        $order = OrderList::find()->ids($model->orderId)->hidden()->one();
        if (empty($order)) {
            $model->addError('orderId', 'Заказ не найден.');
        } else {
            if (empty($model->typeOrder)) {
                $model->typeOrder = $order->type;
            }
            $model->year = Yii::$app->formatter->asDate($model->date, 'php:Y');
            $model->month = Yii::$app->formatter->asDate($model->date, 'php:m');
            $model->date = Yii::$app->formatter->asDate($model->date, 'php:Y-m-d');
            switch ($model->typeOrder) {
                case OrderList::TYPE_PRODUCT:
                    $model->unitId = Yii::$app->user->identity->unitId;
                    $model->name = ProductEntity::getName($this->getOrderRationing($model));
                    if (empty($model->norma) && !empty($model->orderRationingDataId)) {
                        /** @var OrderRationingData $selectPoint */
                        $selectPoint = OrderRationingData::find()
                            ->ids($model->orderRationingDataId)
                            ->one();
                        $normaAll = OrderRationingData::find()
                            ->andWhere([
                                'rationingId' => $selectPoint->rationingId,
                                'point' => $selectPoint->point,
                                'specialId' => $model->personalRelation->specialId
                            ])->hidden()
                            ->sum('normaAll');
                        $model->norma = $normaAll;
                    }
                    break;
                case OrderList::TYPE_DEVICE_REPAIR:
                    /** @var Device $device */
                    $device = Device::find()->ids($model->deviceRepairId)->hidden()->one();
                    if (empty($device)) {
                        $model->addError('deviceRepairId', 'Не верно указано СИ');
                    } else {
                        $model->inventoryNumber = $device->inventoryNumber;
                        $model->number = $device->factoryNumber;
                        $model->name = DeviceRepairEntity::getName($device->deviceNameRelation->name . ' ' . $device->deviceTypeRelation->name);
                    }
                    break;
                case OrderList::TYPE_STAND_VERIFICATION:
                    /** @var Device $device */
                    $device = Device::find()->ids($model->deviceVerificationId)->hidden()->one();
                    if (empty($device)) {
                        $model->addError('deviceVerificationId', 'Не верно указано СИ');
                    } else {
                        if (empty($model->norma)) {
                            $model->norma = $device->norma;
                        }
                        $model->inventoryNumber = $device->inventoryNumber;
                        $model->number = $device->factoryNumber;
                        $model->name = StandEntity::getName($device->deviceNameRelation->getFullName());
                    }
                    break;
                case OrderList::TYPE_DEVICE_VERIFICATION:
                    /** @var Device $device */
                    $device = Device::find()->ids($model->deviceVerificationId)->hidden()->one();
                    if (empty($device)) {
                        $model->addError('deviceVerificationId', 'Не верно указано СИ');
                    } else {
                        if (empty($model->norma)) {
                            $model->norma = $device->norma;
                        }
                        $model->inventoryNumber = $device->inventoryNumber;
                        $model->number = $device->factoryNumber;
                        $model->name = DeviceEntity::getName($device->deviceNameRelation->getFullName());
                    }
                    break;
                case OrderList::TYPE_STAND:
                    /** @var Stand $stand */
                    $stand = Stand::find()->ids($model->standId)->hidden()->one();
                    if (empty($stand)) {
                        $model->addError('standId', 'Не верно указан стенд');
                    } else {
                        if (empty($model->norma)) {
                            $model->norma = $stand->standardHours;
                        }
                        $model->inventoryNumber = $stand->inventoryNumber;
                        $model->number = $stand->number;
                        $model->name = StandEntity::getName($stand->number);
                    }
                    break;
            }
        }
    }

    /**
     * @param Event $event
     * @return void
     * @throws InvalidConfigException
     */
    public function afterInsert(Event $event): void
    {
        /** @var PresentationBook $model */
        $model = $event->sender;
        $order = OrderList::find()->ids($model->orderId)->hidden()->one();
        if (empty($order)) {
            $model->addError('orderId', 'Заказ не найден.');
        } else {
            $model->date = Yii::$app->formatter->asDate($model->date, 'php:Y-m-d');
            if ($model->typeOrder === OrderList::TYPE_PRODUCT) {
                /** @var OrderRationing $rationing */
                $rationing = OrderRationing::find()->ids($model->orderRationingId)->one();
                if (empty($rationing)) {
                    $model->addError('orderRationingId', 'Не верно указана нормировка.');
                } else {
                    if (!empty($model->orderRationingDataId)) {
                        /** @var OrderRationingData $selectPoint */
                        $selectPoint = OrderRationingData::find()
                            ->ids($model->orderRationingDataId)
                            ->one();
                        $orderRationingData = OrderRationingData::find()
                            ->andWhere([
                                'rationingId' => $rationing->id,
                                'point' => $selectPoint->point,
                                'specialId' => $model->personalRelation->specialId
                            ])->hidden()
                            ->orderBy(['sort' => SORT_ASC])
                            ->all();
                        foreach ($orderRationingData as $rationingData) {
                            $this->addData($model, $rationingData, $rationing);
                        }
                    } else {
                        foreach ($rationing->orderRationingDatasRelation as $rationingData) {
                            $this->addData($model, $rationingData, $rationing);
                        }
                    }
                }
            }
        }
    }

    /**
     * @param Event $event
     * @return void
     * @throws InvalidConfigException
     */
    public function afterFind(Event $event): void
    {
        /** @var PresentationBook $model */
        $model = $event->sender;
        if ($model->status === PresentationBook::STATUS_PRESENT_NOT_CARD) {
            $model->colorCell = [
                'class' => "table-danger"
            ];
        }
        if ($model->status === PresentationBook::STATUS_NOT_PRESENT) {
            $model->colorCell = [
                'class' => "table-warning"
            ];
        }
        if ($model->status === PresentationBook::STATUS_PRESENT_CLOSE_JOB) {
            $model->colorCell = [
                'class' => "table-info"
            ];
        }
        $model->date = Yii::$app->formatter->asDate($model->date, 'php:d.m.Y');
    }

    /**
     * @param Event $event
     * @return void
     * @throws InvalidConfigException
     */
    public function beforeUpdate(Event $event): void
    {
        /** @var PresentationBook $model */
        $model = $event->sender;
        $model->date = Yii::$app->formatter->asDate($model->date, 'php:Y-m-d');
    }

    /**
     * @param PresentationBook $model
     * @param OrderRationingData $rationingData
     * @param OrderRationing $rationing
     * @return void
     * @throws Exception
     */
    protected function addData(PresentationBook $model, OrderRationingData $rationingData, OrderRationing $rationing): void
    {
        if ($model->personalRelation->specialId === $rationingData->specialId) {
            if ($model->checkClose) {
                if ($rationingData->stayNorma > 0) {
                    $add = new PresentationBookDataProduct([
                        'bookId' => $model->id,
                        'orderRationingId' => $rationing->id,
                        'orderRationingDataId' => $rationingData->id,
                        'norma' => $rationingData->stayNorma,
                        'sort' => $rationingData->sort
                    ]);
                    $add->save();
                }
            }
            else {
                $add = new PresentationBookDataProduct([
                    'bookId' => $model->id,
                    'orderRationingId' => $rationing->id,
                    'orderRationingDataId' => $rationingData->id,
                    'norma' => $rationingData->normaAll,
                    'sort' => $rationingData->sort
                ]);
                $add->save();
            }
        }
    }

    /**
     * @param PresentationBook $model
     * @return string
     */
    private function getOrderRationing(PresentationBook $model): string
    {
        /** @var OrderRationing $rationing */
        $rationing = OrderRationing::find()->ids($model->orderRationingId)->one();
        if (empty($rationing)) {
            $model->addError('orderRationingId', 'Не верно указана нормировка.');
        } else {
            if (!empty($model->orderRationingDataId)) {
                /** @var OrderRationingData $orderRationingData */
                $orderRationingData = OrderRationingData::find()
                    ->hidden()
                    ->ids($model->orderRationingDataId)
                    ->one();
                return 'п. ' . $orderRationingData->point . ' ' . $orderRationingData->name;
            } else {
                return $rationing->name;
            }
        }
        return '';
    }
}
