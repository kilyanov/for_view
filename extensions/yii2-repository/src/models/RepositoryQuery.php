<?php

declare(strict_types=1);

namespace kilyanov\repository\models;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[Repository]].
 *
 * @see Repository
 */
class RepositoryQuery extends ActiveQuery
{
    /**
     * @inheritdoc
     * @return Repository[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Repository|array|null
     */
    public function one($db = null): Repository|array|null
    {
        return parent::one($db);
    }
}
