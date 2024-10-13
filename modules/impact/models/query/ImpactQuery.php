<?php

declare(strict_types=1);

namespace app\modules\impact\models\query;

use app\common\database\traits\DefaultActiveQueryTrait;
use app\common\database\traits\HiddenActiveQueryTrait;
use app\modules\impact\models\Impact;
use yii\db\ActiveQuery;

class ImpactQuery extends ActiveQuery
{
    use DefaultActiveQueryTrait;
    use HiddenActiveQueryTrait;

    /**
     * {@inheritdoc}
     * @return Impact[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Impact|array|null
     */
    public function one($db = null): array|Impact|null
    {
        return parent::one($db);
    }
}
