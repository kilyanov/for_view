<?php

declare(strict_types=1);

namespace app\modules\industry\behaviors;

use app\modules\industry\models\Machine;
use app\modules\industry\models\OrderRationingData;
use app\modules\industry\models\OrderRationingDataClose;
use app\modules\rationing\models\RationingProductData;
use Exception;
use yii\behaviors\AttributeBehavior;
use yii\db\BaseActiveRecord;
use yii\helpers\ArrayHelper;

/**
 *
 * @property-write mixed $attribute
 * @property-write mixed $attributeFind
 */
class RationingBehavior extends AttributeBehavior
{

    /**
     * @return string[]
     */
    public function events(): array
    {
        return [
            BaseActiveRecord::EVENT_BEFORE_INSERT => 'setAttribute',
            BaseActiveRecord::EVENT_BEFORE_UPDATE => 'setAttribute',
            BaseActiveRecord::EVENT_AFTER_FIND => 'setAttributeFind',
        ];
    }

    /**
     * @param $event
     * @return void
     * @throws Exception
     */
    public function setAttribute($event): void
    {
        /** @var OrderRationingData $owner */
        $owner = $this->owner;
        if (!empty($owner->countItems) && !empty($owner->norma)) {
            $owner->normaAll = $owner->countItems * $owner->norma;
        }
        if ($owner instanceof RationingProductData) {
            if (!empty($owner->machineId)) {
                $machine = Machine::find()->ids($owner->machineId)->product($owner->rationingRelation->productId)->one();
                if (empty($machine)) {
                    $owner->machineId = null;
                }
            }
        }
    }

    /**
     * @param $event
     * @return void
     * @throws Exception
     */
    public function setAttributeFind($event): void
    {
        /** @var OrderRationingData $owner */
        $owner = $this->owner;
        if ($owner instanceof OrderRationingData) {
            $data = $this->getListCloseNorma($owner);
            $closeNorma = ArrayHelper::getValue($data, $owner->id);
            $owner->closeNorma = $closeNorma === null ? 0.00 : (float)$closeNorma['data'];
            $owner->stayNorma = $owner->normaAll - $owner->closeNorma;
            if ($owner->normaAll == $owner->closeNorma) {
                $owner->colorCell = ['class' => "table-success"];
            }
            else if($owner->closeNorma > 0 && $owner->normaAll != $owner->closeNorma) {
                $owner->colorCell = ['class' => "table-danger"];
            }
        }
    }

    /**
     * @param $owner
     * @return array
     */
    protected function getListCloseNorma($owner): array
    {
        static $closeNormaList = [];
        if (empty($closeNormaList)) {
            $query = OrderRationingData::find()
                ->select('id')
                ->andWhere(['rationingId' => $owner->rationingId]);
            $closeNormaList = OrderRationingDataClose::find()
                ->select(['orderRationingDataId as id', 'SUM(norma) as data'])
                ->andWhere(['orderRationingDataId' => $query])
                ->groupBy('orderRationingDataId')->asArray()->all();
            $closeNormaList = ArrayHelper::index($closeNormaList, 'id');
        }

        return $closeNormaList;
    }
}
