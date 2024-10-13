<?php

declare(strict_types=1);

namespace app\modules\product\models\query;

use app\common\database\traits\DefaultActiveQueryTrait;
use app\common\database\traits\HiddenActiveQueryTrait;
use app\modules\product\models\ProductNode;
use yii\db\ActiveQuery;

class ProductNodeQuery extends ActiveQuery
{
    use DefaultActiveQueryTrait;
    use HiddenActiveQueryTrait;

    /**
     * {@inheritdoc}
     * @return ProductNode[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return ProductNode|array|null
     */
    public function one($db = null): array|ProductNode|null
    {
        return parent::one($db);
    }
}
