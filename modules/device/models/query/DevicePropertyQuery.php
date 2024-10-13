<?php

declare(strict_types=1);

namespace app\modules\device\models\query;

use app\common\database\traits\DefaultActiveQueryTrait;
use app\common\database\traits\HiddenActiveQueryTrait;
use app\modules\device\models\DeviceProperty;
use app\modules\device\trait\StatusActiveQueryTrait;
use yii\db\ActiveQuery;

class DevicePropertyQuery extends ActiveQuery
{
    use DefaultActiveQueryTrait;
    use HiddenActiveQueryTrait;
    use StatusActiveQueryTrait;

    /**
     * {@inheritdoc}
     * @return DeviceProperty[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return DeviceProperty|array|null
     */
    public function one($db = null): DeviceProperty|array|null
    {
        return parent::one($db);
    }
}
