<?php

declare(strict_types=1);

namespace app\modules\personal\widgets;

use app\common\interface\StatusAttributeInterface;
use app\modules\personal\models\Personal;
use kartik\select2\Select2;
use ReflectionException;
use yii\base\InvalidConfigException;

class PersonalListWidget extends Select2
{
    /**
     * @var int
     */
    public int $typeSalary = Personal::TYPE_SALARY_NO;

    /**
     * @var int
     */
    public int $status = StatusAttributeInterface::STATUS_ACTIVE;

    /**
     * @var string|null
     */
    public ?string $groupId = null;

    /**
     * @throws ReflectionException
     * @throws InvalidConfigException
     */
    public function run(): void
    {
        $this->data = Personal::find()
            ->status($this->status)
            ->typeSalary($this->typeSalary)
            ->group($this->groupId)
            ->hidden()
            ->type()
            ->order()
            ->asDropDown();

        parent::run();
    }
}
