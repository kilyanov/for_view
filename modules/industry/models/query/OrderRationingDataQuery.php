<?php

declare(strict_types=1);

namespace app\modules\industry\models\query;

use app\common\database\traits\DefaultActiveQueryTrait;
use app\common\database\traits\HiddenActiveQueryTrait;
use app\modules\industry\models\OrderRationingData;
use yii\db\ActiveQuery;

class OrderRationingDataQuery extends ActiveQuery
{
    use DefaultActiveQueryTrait;
    use HiddenActiveQueryTrait;

    /**
     * {@inheritdoc}
     * @return OrderRationingData[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return OrderRationingData|array|null
     */
    public function one($db = null): OrderRationingData|array|null
    {
        return parent::one($db);
    }
}
