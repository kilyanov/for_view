<?php

declare(strict_types=1);

namespace app\modules\rationing\models\query;

use app\common\database\traits\DefaultActiveQueryTrait;
use app\common\database\traits\HiddenActiveQueryTrait;
use app\modules\rationing\models\RationingProductData;
use yii\db\ActiveQuery;

class RationingProductDataQuery extends ActiveQuery
{
    use HiddenActiveQueryTrait;
    use DefaultActiveQueryTrait;

    /**
     * {@inheritdoc}
     * @return RationingProductData[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return RationingProductData|array|null
     */
    public function one($db = null): RationingProductData|array|null
    {
        return parent::one($db);
    }
}
