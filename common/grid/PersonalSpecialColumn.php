<?php

declare(strict_types=1);

namespace app\common\grid;

use app\modules\personal\modules\special\models\PersonalSpecial;
use Exception;
use yii\db\ActiveRecord;
use yii\grid\DataColumn;

/**
 *
 * @property-read array $specials
 */
class PersonalSpecialColumn extends DataColumn
{
    /**
     * @var string
     */
    public $attribute = 'specialId';

    /**
     * @var string
     */
    public string $relation = 'specialRelation';

    /**
     * @throws Exception
     */
    public function getDataCellValue($model, $key, $index): ?string
    {
        /** @var ActiveRecord $model */

        if ($this->value === null) {
            $specials = $this->getSpecials();
            $attribute = $this->attribute;

            return $specials[$model->$attribute] ?? '';
        }

        return parent::getDataCellValue($model, $key, $index);
    }

    /**
     * @return string
     */
    protected function renderFilterCellContent(): string
    {
        $this->filter = $this->getSpecials();
        return parent::renderFilterCellContent();
    }

    /**
     * @return array
     */
    protected function getSpecials(): array
    {
        static $specials = [];

        if (empty($specials)) {
            $specials = PersonalSpecial::asDropDown();
        }

        return $specials;
    }
}
