<?php

declare(strict_types=1);

namespace app\modules\device\behaviors;

use app\modules\device\common\ColorCell;
use app\modules\device\models\Device;
use yii\behaviors\AttributeBehavior;
use yii\db\BaseActiveRecord;

/**
 *
 * @property-write mixed $virtualAttr
 * @property-read array $colorCell
 */
class DeviceVirtualAttributesBehavior extends AttributeBehavior
{

    /**
     * @return string[]
     */
    public function events(): array
    {
        return [
            BaseActiveRecord::EVENT_AFTER_FIND => 'setVirtualAttr'
        ];
    }

    /**
     * @param $event
     * @return void
     */
    public function setVirtualAttr($event): void
    {
        /** @var Device $owner */
        $owner = $this->owner;
        $owner->linkView = $owner->deviceInfoVerificationRelation?->linkView;
        $owner->linkBase = $owner->deviceInfoVerificationRelation?->linkBase;
        $owner->certificateNumber = $owner->deviceInfoVerificationRelation?->certificateNumber;
        $owner->colorCell = $this->getColorCell();
    }

    /**
     * @return array
     */
    protected function getColorCell(): array
    {
        /** @var Device $owner */
        $owner = $this->owner;
        $colorCell = new ColorCell([
            'dateCurrentVerification' => !empty($owner->deviceVerificationRelation) ?
                $owner->deviceVerificationRelation->verification_date : null,
            'deviceStandard' => $owner->deviceStandardRelation !== null,
            'verificationPeriod' => $owner->verificationPeriod
        ]);

        return $colorCell->getColorVerification();
    }
}
