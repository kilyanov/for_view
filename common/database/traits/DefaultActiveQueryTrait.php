<?php

declare(strict_types=1);

namespace app\common\database\traits;

use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

trait DefaultActiveQueryTrait
{
    /**
     * @param string|array $ids
     *
     * @return ActiveQuery
     */
    public function ids(string|array $ids): ActiveQuery
    {
        /** @var ActiveQuery $this */
        return $this->andWhere([$this->modelClass::tableName() . '.[[id]]' => $ids]);
    }

    /**
     * @param string $attribute
     * @param int $sort
     * @return ActiveQuery
     */
    public function order(string $attribute = 'sort', int $sort = SORT_ASC): ActiveQuery
    {
        /** @var ActiveQuery $this */
        return $this->orderBy([$this->modelClass::tableName() . '.[[' . $attribute . ']]' => $sort]);
    }

    /**
     * @return array
     */
    public function asDropDown(): array
    {
        return ArrayHelper::map(
            $this->all(),
            'id',
            static function ($model) {
                return $model->getFullName();
            }
        );
    }
}
