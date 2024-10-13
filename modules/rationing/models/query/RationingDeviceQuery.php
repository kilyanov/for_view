<?php

declare(strict_types=1);

namespace app\modules\rationing\models\query;

use app\common\database\traits\DefaultActiveQueryTrait;
use app\common\database\traits\HiddenActiveQueryTrait;
use app\modules\rationing\models\RationingDevice;
use yii\db\ActiveQuery;

class RationingDeviceQuery extends ActiveQuery
{
    use HiddenActiveQueryTrait;
    use DefaultActiveQueryTrait;

    /**
     * {@inheritdoc}
     * @return RationingDevice[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return RationingDevice|array|null
     */
    public function one($db = null): array|RationingDevice|null
    {
        return parent::one($db);
    }
}
