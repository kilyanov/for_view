<?php

declare(strict_types=1);

namespace kilyanov\behaviors\common;

use DateTime;
use yii\behaviors\TimestampBehavior as TimestampBehaviorAlias;

class TimestampBehavior extends TimestampBehaviorAlias
{
    /**
     * @var string
     */
    public $createdAtAttribute = 'createdAt';

    /**
     * @var string
     */
    public $updatedAtAttribute = 'updatedAt';

    /**
     * @param $event
     * @return string
     */
    protected function getValue($event): string
    {
        return $this->value ?: (new DateTime())->format('Y-m-d H:i:s');
    }
}
