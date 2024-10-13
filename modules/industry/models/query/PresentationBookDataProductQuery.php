<?php

declare(strict_types=1);

namespace app\modules\industry\models\query;

use app\common\database\traits\DefaultActiveQueryTrait;
use app\modules\industry\models\PresentationBookDataProduct;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\app\modules\industry\models\PresentationBookDataProduct]].
 *
 * @see PresentationBookDataProduct
 */
class PresentationBookDataProductQuery extends ActiveQuery
{
    use DefaultActiveQueryTrait;

    /**
     * {@inheritdoc}
     * @return PresentationBookDataProduct[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return PresentationBookDataProduct|array|null
     */
    public function one($db = null): array|PresentationBookDataProduct|null
    {
        return parent::one($db);
    }
}
