<?php

declare(strict_types=1);

namespace app\modules\device\models\query;

use app\common\database\traits\DefaultActiveQueryTrait;
use app\common\database\traits\HiddenActiveQueryTrait;
use app\modules\device\models\DeviceInfoVerification;
use app\modules\device\trait\StatusActiveQueryTrait;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\app\modules\device\models\DeviceInfoVerification]].
 *
 * @see DeviceInfoVerification
 */
class DeviceInfoVerificationQuery extends ActiveQuery
{
    use DefaultActiveQueryTrait;
    use HiddenActiveQueryTrait;
    use StatusActiveQueryTrait;

    /**
     * {@inheritdoc}
     * @return DeviceInfoVerification[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return DeviceInfoVerification|array|null
     */
    public function one($db = null): DeviceInfoVerification|array|null
    {
        return parent::one($db);
    }
}
