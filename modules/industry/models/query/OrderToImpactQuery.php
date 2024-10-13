<?php

declare(strict_types=1);

namespace app\modules\industry\models\query;

use app\common\database\traits\DefaultActiveQueryTrait;
use app\common\database\traits\HiddenActiveQueryTrait;
use app\modules\industry\models\OrderToImpact;
use yii\db\ActiveQuery;

class OrderToImpactQuery extends ActiveQuery
{
    use DefaultActiveQueryTrait;
    use HiddenActiveQueryTrait;

    /**
     * {@inheritdoc}
     * @return OrderToImpact[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return OrderToImpact|array|null
     */
    public function one($db = null): OrderToImpact|array|null
    {
        return parent::one($db);
    }

    /**
     * @param string $orderId
     * @return OrderToImpactQuery
     */
    public function orderId(string $orderId): OrderToImpactQuery
    {
        return $this->andWhere([OrderToImpact::tableName() . '.[[orderId]]' => $orderId]);
    }
}
