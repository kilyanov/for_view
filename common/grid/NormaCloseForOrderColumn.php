<?php

declare(strict_types=1);

namespace app\common\grid;

use app\modules\industry\models\OrderList;
use app\modules\industry\models\OrderRationing;
use app\modules\industry\models\OrderRationingData;
use app\modules\industry\models\OrderRationingDataClose;
use Exception;
use yii\helpers\ArrayHelper;

class NormaCloseForOrderColumn extends PageSummaryDataColumn
{
    /**
     * @throws Exception
     */
    public function getDataCellValue($model, $key, $index): string|float|null
    {
        /** @var OrderList $model */
        if ($this->value === null) {
            $orderSum = $this->getOrderSum();
            $sum = ArrayHelper::getValue($orderSum, $model->id);

            $this->setTotal((float)ArrayHelper::getValue($sum,'sumHour', 0.00));

            return $sum == null ? '' : $sum['sumHour'];
        }

        return parent::getDataCellValue($model, $key, $index);
    }

    /**
     * @return array
     */
    protected function getOrderSum(): array
    {
        static $orderSum = [];
        if (empty($orderSum)) {
            $orderList = OrderList::find()->select('id');
            $orderRationingList = OrderRationing::find()
                ->select(OrderRationing::tableName() . '.[[id]]')
                ->andWhere([OrderRationing::tableName() . '.[[orderId]]' => $orderList]);
            $orderRationingDataList = OrderRationingData::find()
                ->select([OrderRationingData::tableName() . '.[[id]]'])
                ->andWhere(['rationingId' => $orderRationingList]);
            $data = OrderRationingDataClose::find()
                ->joinWith(['orderRationingDataRelation.rationingRelation'])
                ->select([
                    OrderRationing::tableName() . '.[[orderId]]',
                    'count('. OrderRationing::tableName() . '.[[orderId]]) as id',
                    'SUM(' . OrderRationingDataClose::tableName() . '.[[norma]]) as sumHour'])
                ->andWhere([ OrderRationingDataClose::tableName() . '.[[orderRationingDataId]]' => $orderRationingDataList])
                ->groupBy(OrderRationing::tableName() . '.[[orderId]]')
                ->asArray()
                ->all();
            $orderSum = ArrayHelper::index($data, 'orderId');
        }

        return $orderSum;
    }
}
