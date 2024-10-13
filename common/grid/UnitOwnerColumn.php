<?php

declare(strict_types=1);

namespace app\common\grid;

use app\modules\industry\models\PresentationBook;
use app\modules\unit\models\Unit;
use Exception;
use yii\grid\DataColumn;

/**
 *
 * @property-read array $units
 */
class UnitOwnerColumn extends DataColumn
{
    /**
     * @throws Exception
     */
    public function getDataCellValue($model, $key, $index): ?string
    {
        /** @var PresentationBook $model */
        if ($model->deviceRepairRelation) {
            return $model->deviceRepairRelation->deviceToUnitRelation->unitRelation->getFullName();
        }
        /** @var PresentationBook $model */
        if ($model->deviceVerificationRelation) {
            return $model->deviceVerificationRelation->deviceToUnitRelation->unitRelation->getFullName();
        }

        return parent::getDataCellValue($model, $key, $index);
    }

    /**
     * @return array
     * @throws Exception
     */
    protected function getUnits(): array
    {
        static $units = [];

        if (empty($units)) {
            $units = Unit::asDropDown(['showParent' => true]);
        }

        return $units;
    }
}
