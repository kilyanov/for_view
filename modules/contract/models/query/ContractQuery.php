<?php

declare(strict_types=1);

namespace app\modules\contract\models\query;

use app\common\database\traits\DefaultActiveQueryTrait;
use app\common\database\traits\HiddenActiveQueryTrait;
use app\modules\contract\models\Contract;
use yii\db\ActiveQuery;

class ContractQuery extends ActiveQuery
{
    use DefaultActiveQueryTrait;
    use HiddenActiveQueryTrait;

    /**
     * {@inheritdoc}
     * @return Contract[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Contract|array|null
     */
    public function one($db = null): Contract|array|null
    {
        return parent::one($db);
    }

    /**
     * @param string $attribute
     * @param int $sort
     * @return ContractQuery
     */
    public function order(string $attribute = 'validityPeriod', int $sort = SORT_DESC): ContractQuery
    {
        return $this->orderBy([Contract::tableName() . '.[[' . $attribute . ']]' => $sort]);
    }

    /**
     * @param int|array|null $status
     * @return ContractQuery
     */
    public function status(null|int|array $status): ContractQuery
    {
        return $this->andFilterWhere([Contract::tableName() . '.[[status]]' => $status]);
    }
}
