<?php

declare(strict_types=1);

namespace app\modules\industry\models\query;

use app\common\database\traits\DefaultActiveQueryTrait;
use app\modules\industry\models\OrderRationingDataClose;
use yii\db\ActiveQuery;

class OrderRationingDataCloseQuery extends ActiveQuery
{
    use DefaultActiveQueryTrait;

    /**
     * {@inheritdoc}
     * @return OrderRationingDataClose[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return OrderRationingDataClose|array|null
     */
    public function one($db = null): OrderRationingDataClose|array|null
    {
        return parent::one($db);
    }
}
