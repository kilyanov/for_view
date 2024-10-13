<?php

declare(strict_types=1);

namespace app\modules\resource\models\query;

use app\common\database\traits\DefaultActiveQueryTrait;
use app\common\database\traits\HiddenActiveQueryTrait;
use app\modules\resource\models\Resource;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[Resource]].
 *
 * @see Resource
 */
class ResourceQuery extends ActiveQuery
{
    use DefaultActiveQueryTrait;
    use HiddenActiveQueryTrait;

    /**
     * {@inheritdoc}
     * @return Resource[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return array|Resource|null
     */
    public function one($db = null):Resource|array|null
    {
        return parent::one($db);
    }

    /**
     * @param string $attribute
     * @param int $sort
     * @return ResourceQuery
     */
    public function order(string $attribute = 'name', int $sort = SORT_ASC): ResourceQuery
    {
        /** @var ResourceQuery $this */
        return $this->orderBy([$this->modelClass::tableName() . '.[[' . $attribute . ']]' => $sort]);
    }
}
