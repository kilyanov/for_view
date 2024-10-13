<?php

declare(strict_types=1);

namespace app\modules\device\models\query;

use app\common\database\traits\DefaultActiveQueryTrait;
use app\common\database\traits\HiddenActiveQueryTrait;
use app\modules\device\models\DeviceVerification;
use app\modules\device\trait\StatusActiveQueryTrait;
use yii\db\ActiveQuery;

class DeviceVerificationQuery extends ActiveQuery
{
    use DefaultActiveQueryTrait;
    use HiddenActiveQueryTrait;
    use StatusActiveQueryTrait;

    /**
     * {@inheritdoc}
     * @return DeviceVerification[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return DeviceVerification|array|null
     */
    public function one($db = null): array|DeviceVerification|null
    {
        return parent::one($db);
    }
}
