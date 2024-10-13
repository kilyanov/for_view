<?php

declare(strict_types=1);

namespace app\modules\industry\models\query;

use app\common\database\traits\DefaultActiveQueryTrait;
use app\common\database\traits\HiddenActiveQueryTrait;
use app\modules\industry\models\Machine;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\app\modules\industry\models\Machine]].
 *
 * @see Machine
 */
class MachineQuery extends ActiveQuery
{
    use DefaultActiveQueryTrait;
    use HiddenActiveQueryTrait;

    /**
     * {@inheritdoc}
     * @return Machine[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Machine|array|null
     */
    public function one($db = null): array|Machine|null
    {
        return parent::one($db);
    }

    /**
     * @param string|null $productId
     * @return self
     */
    public function product(?string $productId): self
    {
        return $this->andWhere([Machine::tableName() . '.[[productId]]' => $productId]);
    }
}
