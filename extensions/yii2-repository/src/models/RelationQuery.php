<?php

declare(strict_types=1);

namespace kilyanov\repository\models;

use yii\db\ActiveQuery;

class RelationQuery extends ActiveQuery
{
    /**
     * @inheritdoc
     * @return Relation[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Relation|array|null
     */
    public function one($db = null): Relation|array|null
    {
        return parent::one($db);
    }
}
