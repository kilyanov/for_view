<?php

declare(strict_types=1);

namespace app\modules\industry\modules\machine\widgets;

use app\modules\industry\models\Machine;
use kartik\select2\Select2;
use ReflectionException;
use yii\base\InvalidConfigException;

class MachineWidget extends Select2
{
    /**
     * @var string|null
     */
    public ?string $productId;

    /**
     * @throws ReflectionException
     * @throws InvalidConfigException
     */
    public function run(): void
    {
        $this->data = Machine::find()
            ->hidden()
            ->product($this->productId)
            ->order()
            ->asDropDown();

        parent::run();
    }
}
