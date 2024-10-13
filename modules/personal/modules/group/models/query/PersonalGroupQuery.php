<?php

declare(strict_types=1);

namespace app\modules\personal\modules\group\models\query;

use app\common\database\traits\DefaultActiveQueryTrait;
use app\common\database\traits\HiddenActiveQueryTrait;
use app\modules\personal\modules\group\models\PersonalGroup;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[PersonalGroup]].
 *
 * @see PersonalGroup
 */
class PersonalGroupQuery extends ActiveQuery
{
    use DefaultActiveQueryTrait;
    use HiddenActiveQueryTrait;

    /**
     * {@inheritdoc}
     * @return PersonalGroup[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return PersonalGroup|array|null
     */
    public function one($db = null): PersonalGroup|array|null
    {
        return parent::one($db);
    }
}
