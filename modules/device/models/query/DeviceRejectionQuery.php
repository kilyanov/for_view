<?php

declare(strict_types=1);

namespace app\modules\device\models\query;

use app\common\database\traits\DefaultActiveQueryTrait;
use app\common\database\traits\HiddenActiveQueryTrait;
use app\modules\device\models\DeviceRejection;
use app\modules\device\trait\StatusActiveQueryTrait;
use yii\db\ActiveQuery;

class DeviceRejectionQuery extends ActiveQuery
{
    use DefaultActiveQueryTrait;
    use HiddenActiveQueryTrait;
    use StatusActiveQueryTrait;

    /**
     * {@inheritdoc}
     * @return DeviceRejection[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return DeviceRejection|array|null
     */
    public function one($db = null): array|DeviceRejection|null
    {
        return parent::one($db);
    }
}
