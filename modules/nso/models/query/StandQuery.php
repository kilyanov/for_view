<?php

declare(strict_types=1);

namespace app\modules\nso\models\query;

use app\common\database\traits\DefaultActiveQueryTrait;
use app\common\database\traits\HiddenActiveQueryTrait;
use app\modules\nso\models\Stand;
use yii\db\ActiveQuery;

class StandQuery extends ActiveQuery
{
    use DefaultActiveQueryTrait;
    use HiddenActiveQueryTrait;

    /**
     * {@inheritdoc}
     * @return Stand[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return array|Stand|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
