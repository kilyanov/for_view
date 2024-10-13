<?php

declare(strict_types=1);

namespace app\modules\device\models\query;

use app\modules\device\models\DeviceConservation;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the ActiveQuery class for [[\app\modules\device\models\DeviceConservation]].
 *
 * @see \app\modules\device\models\DeviceConservation
 */
class DeviceConservationQuery extends ActiveQuery
{
    /**
     * {@inheritdoc}
     * @return DeviceConservation[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @param $db
     * @return array|ActiveRecord|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
