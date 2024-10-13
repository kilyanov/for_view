<?php

declare(strict_types=1);

namespace app\modules\industry\models\query;

use app\common\database\traits\DefaultActiveQueryTrait;
use app\common\database\traits\HiddenActiveQueryTrait;
use app\modules\industry\models\OrderToProduct;
use yii\db\ActiveQuery;

class OrderToProductQuery extends ActiveQuery
{
    use DefaultActiveQueryTrait;
    use HiddenActiveQueryTrait;

    /**
     * {@inheritdoc}
     * @return OrderToProduct[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return OrderToProduct|array|null
     */
    public function one($db = null): array|OrderToProduct|null
    {
        return parent::one($db);
    }

    /**
     * @param string $orderId
     * @return OrderToProductQuery
     */
    public function orderId(string $orderId): OrderToProductQuery
    {
        return $this->andWhere([OrderToProduct::tableName() . '.[[orderId]]' => $orderId]);
    }
}
