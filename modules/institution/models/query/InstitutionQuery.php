<?php

declare(strict_types=1);

namespace app\modules\institution\models\query;

use app\common\database\traits\DefaultActiveQueryTrait;
use app\common\database\traits\HiddenActiveQueryTrait;
use app\modules\institution\models\Institution;
use yii\db\ActiveQuery;

class InstitutionQuery extends ActiveQuery
{
    use DefaultActiveQueryTrait;
    use HiddenActiveQueryTrait;

    /**
     * {@inheritdoc}
     * @return Institution[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Institution|array|null
     */
    public function one($db = null): array|Institution|null
    {
        return parent::one($db);
    }
}
