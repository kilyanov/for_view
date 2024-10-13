<?php

declare(strict_types=1);

namespace app\modules\device\trait;

use app\modules\device\interface\StatusAttributeInterface;
use yii\db\ActiveQuery;

trait StatusActiveQueryTrait
{
    /**
     * @param string|array|null $status
     * @return ActiveQuery
     */
    public function status(string|array|null $status = StatusAttributeInterface::STATUS_ACTIVE): ActiveQuery
    {
        $this->andFilterWhere([$this->modelClass::tableName() . '.[[status]]' => $status]);

        return $this;
    }
}
