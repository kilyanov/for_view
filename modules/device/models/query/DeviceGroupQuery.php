<?php

declare(strict_types=1);

namespace app\modules\device\models\query;

use app\common\database\traits\DefaultActiveQueryTrait;
use app\common\database\traits\HiddenActiveQueryTrait;
use app\modules\device\models\DeviceGroup;
use app\modules\device\trait\StatusActiveQueryTrait;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\app\modules\device\models\DeviceGroup]].
 *
 * @see DeviceGroup
 */
class DeviceGroupQuery extends ActiveQuery
{
    use DefaultActiveQueryTrait;
    use HiddenActiveQueryTrait;
    use StatusActiveQueryTrait;

    /**
     * {@inheritdoc}
     * @return DeviceGroup[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return DeviceGroup|array|null
     */
    public function one($db = null): DeviceGroup|array|null
    {
        return parent::one($db);
    }
}
