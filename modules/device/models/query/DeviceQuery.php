<?php

namespace app\modules\device\models\query;

use app\common\database\traits\DefaultActiveQueryTrait;
use app\common\database\traits\HiddenActiveQueryTrait;
use app\modules\device\models\Device;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\app\modules\device\models\Device]].
 *
 * @see Device
 */
class DeviceQuery extends ActiveQuery
{
    use DefaultActiveQueryTrait;
    use HiddenActiveQueryTrait;

    /**
     * @param string|array|null $status
     * @return ActiveQuery
     */
    public function status(string|array|null $status = Device::STATUS_VERIFICATION): ActiveQuery
    {
        $this->andFilterWhere([$this->modelClass::tableName() . '.[[status]]' => $status]);

        return $this;
    }

    /**
     * {@inheritdoc}
     * @return Device[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Device|array|null
     */
    public function one($db = null): Device|array|null
    {
        return parent::one($db);
    }
}
