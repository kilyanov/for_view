<?php

declare(strict_types=1);

namespace app\modules\rationing\models\query;

use app\common\database\traits\DefaultActiveQueryTrait;
use app\common\database\traits\HiddenActiveQueryTrait;
use app\modules\rationing\models\RationingDeviceData;
use yii\db\ActiveQuery;

class RationingDeviceDataQuery extends ActiveQuery
{
    use HiddenActiveQueryTrait;
    use DefaultActiveQueryTrait;

    /**
     * {@inheritdoc}
     * @return RationingDeviceData[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return RationingDeviceData|array|null
     */
    public function one($db = null): array|RationingDeviceData|null
    {
        return parent::one($db);
    }
}

