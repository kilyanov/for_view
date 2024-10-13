<?php

declare(strict_types=1);

namespace app\modules\industry\models\query;

use app\common\database\traits\DefaultActiveQueryTrait;
use app\common\database\traits\HiddenActiveQueryTrait;
use app\modules\industry\models\OrderToUnit;
use yii\db\ActiveQuery;

class OrderToUnitQuery extends ActiveQuery
{
    use DefaultActiveQueryTrait;
    use HiddenActiveQueryTrait;

    /**
     * {@inheritdoc}
     * @return OrderToUnit[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return OrderToUnit|array|null
     */
    public function one($db = null): array|OrderToUnit|null
    {
        return parent::one($db);
    }

    /**
     * @param string $orderId
     * @return OrderToUnitQuery
     */
    public function orderId(string $orderId): OrderToUnitQuery
    {
        return $this->andWhere([OrderToUnit::tableName() . '.[[orderId]]' => $orderId]);
    }
}
