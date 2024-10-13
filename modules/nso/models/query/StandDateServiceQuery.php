<?php

declare(strict_types=1);

namespace app\modules\nso\models\query;

use app\common\database\traits\DefaultActiveQueryTrait;
use app\common\database\traits\HiddenActiveQueryTrait;
use app\modules\nso\models\StandDateService;
use yii\db\ActiveQuery;

class StandDateServiceQuery extends ActiveQuery
{
    use DefaultActiveQueryTrait;
    use HiddenActiveQueryTrait;

    /**
     * {@inheritdoc}
     * @return StandDateService[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return StandDateService|array|null
     */
    public function one($db = null): array|StandDateService|null
    {
        return parent::one($db);
    }
}
