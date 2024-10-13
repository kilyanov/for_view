<?php

declare(strict_types=1);

namespace app\modules\product\models\query;

use app\common\database\traits\DefaultActiveQueryTrait;
use app\common\database\traits\HiddenActiveQueryTrait;
use app\modules\product\models\ProductBlock;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

class ProductBlockQuery extends ActiveQuery
{
    use DefaultActiveQueryTrait;
    use HiddenActiveQueryTrait;

    /**
     * {@inheritdoc}
     * @return ProductBlock[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return array|ActiveRecord|null
     */
    public function one($db = null): array|ActiveRecord|null
    {
        return parent::one($db);
    }
}
