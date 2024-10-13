<?php

declare(strict_types=1);

namespace app\modules\personal\models\query;

use app\common\database\traits\DefaultActiveQueryTrait;
use app\common\database\traits\HiddenActiveQueryTrait;
use app\common\interface\StatusAttributeInterface;
use app\modules\personal\interface\TypeAttributeInterface;
use app\modules\personal\models\Personal;
use yii\db\ActiveQuery;

class PersonalQuery extends ActiveQuery
{
    use DefaultActiveQueryTrait;
    use HiddenActiveQueryTrait;

    /**
     * {@inheritdoc}
     * @return Personal[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Personal|array|null
     */
    public function one($db = null): Personal|array|null
    {
        return parent::one($db);
    }

    /**
     * @param int $status
     * @return ActiveQuery
     */
    public function status(int $status = StatusAttributeInterface::STATUS_ACTIVE): ActiveQuery
    {
        $this->andFilterWhere([$this->modelClass::tableName() . '.[[status]]' => $status]);

        return $this;
    }

    /**
     * @param int $typeSalary
     * @return ActiveQuery
     */
    public function typeSalary(int $typeSalary = Personal::TYPE_SALARY_NO): ActiveQuery
    {
        $this->andFilterWhere([$this->modelClass::tableName() . '.[[typeSalary]]' => $typeSalary]);

        return $this;
    }

    /**
     * @param string $attribute
     * @param int $sort
     * @return ActiveQuery
     */
    public function order(string $attribute = 'sort', int $sort = SORT_ASC): ActiveQuery
    {
        /** @var ActiveQuery $this */
        $this->orderBy([
            $this->modelClass::tableName() . '.[[groupId]]' => SORT_ASC,
            $this->modelClass::tableName() . '.[[fistName]]' => SORT_ASC,
            $this->modelClass::tableName() . '.[[type]]' => SORT_ASC,
        ]);

        return $this;
    }

    /**
     * @param string $unitId
     * @return ActiveQuery
     */
    public function unit(string $unitId): ActiveQuery
    {
        $this->andFilterWhere([$this->modelClass::tableName() . '.[[unitId]]' => $unitId]);

        return $this;
    }

    /**
     * @param string $groupId
     * @return ActiveQuery
     */
    public function group(string $groupId): ActiveQuery
    {
        $this->andFilterWhere([$this->modelClass::tableName() . '.[[groupId]]' => $groupId]);

        return $this;
    }

    /**
     * @param int $type
     * @return ActiveQuery
     */
    public function type(int $type = TypeAttributeInterface::TYPE_JOB): ActiveQuery
    {
        $this->andFilterWhere([$this->modelClass::tableName() . '.[[type]]' => $type]);

        return $this;
    }
}
