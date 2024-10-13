<?php

namespace app\modules\contract\models\query;

use app\common\database\traits\DefaultActiveQueryTrait;
use app\common\database\traits\HiddenActiveQueryTrait;
use app\modules\contract\models\ContractSpecification;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

class ContractSpecificationQuery extends ActiveQuery
{
    use DefaultActiveQueryTrait;
    use HiddenActiveQueryTrait;

    /**
     * {@inheritdoc}
     * @return ContractSpecification[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return array|ActiveRecord|null
     */
    public function one($db = null): array|ActiveRecord|null
    {
        return parent::one($db);
    }

    /**
     * @param string $contractId
     * @return self
     */
    public function contract(string $contractId): self
    {
        return  $this->andWhere([ContractSpecification::tableName() . '.[[contractId]]' => $contractId]);
    }
}
