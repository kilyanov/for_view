<?php

declare(strict_types=1);

namespace app\modules\industry\models\query;

use app\common\database\traits\DefaultActiveQueryTrait;
use app\modules\industry\models\PresentationBookDataDeviceRepair;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\app\modules\industry\models\PresentationBookDataDeviceRepair]].
 *
 * @see PresentationBookDataDeviceRepair
 */
class PresentationBookDataDeviceRepairQuery extends ActiveQuery
{
    use DefaultActiveQueryTrait;

    /**
     * {@inheritdoc}
     * @return PresentationBookDataDeviceRepair[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return PresentationBookDataDeviceRepair|array|null
     */
    public function one($db = null): array|PresentationBookDataDeviceRepair|null
    {
        return parent::one($db);
    }
}
