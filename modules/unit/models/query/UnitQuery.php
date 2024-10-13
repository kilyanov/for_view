<?php

declare(strict_types=1);

namespace app\modules\unit\models\query;

use app\common\database\traits\DefaultActiveQueryTrait;
use app\common\database\traits\HiddenActiveQueryTrait;
use app\modules\unit\models\Unit;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

class UnitQuery extends ActiveQuery
{
    use DefaultActiveQueryTrait;
    use HiddenActiveQueryTrait;

    /**
     * {@inheritdoc}
     * @return Unit[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return array|ActiveRecord|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @param bool $parent
     * @return UnitQuery
     */
    public function parent(bool $parent = false): UnitQuery
    {
        if ($parent === true) {
            $this->andWhere([$this->modelClass::tableName() . '.[[parentId]]' => null]);
        }

        return $this;
    }
}
