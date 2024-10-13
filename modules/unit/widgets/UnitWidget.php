<?php

declare(strict_types=1);

namespace app\modules\unit\widgets;

use app\modules\unit\models\Unit;
use kartik\select2\Select2;
use ReflectionException;
use yii\base\InvalidConfigException;

class UnitWidget extends Select2
{
    public const DEFAULT_UNIT_NAME = 'ЦИЛ';

    /**
     * @var bool
     */
    public bool $showParent = true;

    /**
     * @var string|null
     */
    public string|null $parentItem = null;

    /**
     * @throws ReflectionException
     * @throws InvalidConfigException
     */
    public function run(): void
    {
        $query = Unit::find()->hidden();

        if (!empty($this->parentItem)) {
            $subQuery = Unit::find()
                ->select(Unit::tableName() . '.[[id]]')
                ->andWhere([Unit::tableName() . '.[[name]]' => $this->parentItem]);

            $query->andWhere([Unit::tableName() . '.[[id]]' => $subQuery]);
        }

        $this->data = $query->parent($this->showParent)->order()->asDropDown();

        parent::run();
    }
}
