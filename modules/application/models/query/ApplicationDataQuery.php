<?php

declare(strict_types=1);

namespace app\modules\application\models\query;

use app\common\database\traits\DefaultActiveQueryTrait;
use app\modules\application\models\ApplicationData;
use yii\db\ActiveQuery;

class ApplicationDataQuery extends ActiveQuery
{
    use DefaultActiveQueryTrait;

    /**
     * {@inheritdoc}
     * @return ApplicationData[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return ApplicationData|array|null
     */
    public function one($db = null): array|ApplicationData|null
    {
        return parent::one($db);
    }

    /**
     * @param string|null $applicationId
     * @return self
     */
    public function application(?string $applicationId): self
    {
        return $this->andFilterWhere([ApplicationData::tableName() . '.[[applicationId]]' => $applicationId]);
    }
}
