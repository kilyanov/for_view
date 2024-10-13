<?php

declare(strict_types=1);

namespace app\modules\rationing\models\query;

use app\common\database\traits\DefaultActiveQueryTrait;
use app\common\database\traits\HiddenActiveQueryTrait;
use app\modules\rationing\models\RationingProduct;
use yii\db\ActiveQuery;

class RationingProductQuery extends ActiveQuery
{
    use HiddenActiveQueryTrait;
    use DefaultActiveQueryTrait;

    /**
     * {@inheritdoc}
     * @return RationingProduct[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return RationingProduct|array|null
     */
    public function one($db = null): RationingProduct|array|null
    {
        return parent::one($db);
    }
}
