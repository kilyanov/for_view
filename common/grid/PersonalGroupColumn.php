<?php

declare(strict_types=1);

namespace app\common\grid;

use app\modules\personal\modules\group\models\PersonalGroup;
use Exception;
use Yii;
use yii\db\ActiveRecord;
use yii\grid\DataColumn;
use yii\helpers\ArrayHelper;

class PersonalGroupColumn extends DataColumn
{

    public ?string $unitId = null;

    /**
     * @var string
     */
    public $attribute = 'groupId';

    /**
     * @var string
     */
    public string $relation = 'groupRelation';

    /**
     * @return void
     */
    public function init(): void
    {
        if (!Yii::$app->user->isGuest) {
            $this->unitId = null; // todo указать привязку пользователя к подразделению
        }

        parent::init();
    }

    /**
     * @throws Exception
     */
    public function getDataCellValue($model, $key, $index): ?string
    {
        /** @var ActiveRecord $model */
        if ($this->value === null) {
            if ($relation = ArrayHelper::getValue($model, [$this->relation])) {
                $value = ArrayHelper::getValue($relation, ['name']);
                return !empty($value) ? $value : '';
            }
        }

        return parent::getDataCellValue($model, $key, $index);
    }

    /**
     * @return string
     * @throws Exception
     */
    protected function renderFilterCellContent(): string
    {
        /** @var PersonalGroup $filterModel */
        $filterModel = $this->grid->filterModel;
        $this->filter = PersonalGroup::asDropDown(['unitId' => $filterModel->unitId]);

        return parent::renderFilterCellContent();
    }
}
