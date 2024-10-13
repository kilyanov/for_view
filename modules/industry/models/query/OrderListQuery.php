<?php

declare(strict_types=1);

namespace app\modules\industry\models\query;

use app\common\database\traits\DefaultActiveQueryTrait;
use app\common\database\traits\HiddenActiveQueryTrait;
use app\modules\industry\models\OrderList;
use yii\db\ActiveQuery;

class OrderListQuery extends ActiveQuery
{
    use DefaultActiveQueryTrait;
    use HiddenActiveQueryTrait;

    /**
     * {@inheritdoc}
     * @return OrderList[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return OrderList|array|null
     */
    public function one($db = null): array|OrderList|null
    {
        return parent::one($db);
    }

    /**
     * @param int|array|null $status
     * @return OrderListQuery
     */
    public function status(null|int|array $status = OrderList::STATUS_OPEN): OrderListQuery
    {
        return $this->andFilterWhere([OrderList::tableName() . '.[[status]]' => $status]);
    }
}
