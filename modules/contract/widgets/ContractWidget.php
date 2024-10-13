<?php

declare(strict_types=1);

namespace app\modules\contract\widgets;

use app\modules\contract\models\Contract;
use kartik\select2\Select2;
use ReflectionException;
use yii\base\InvalidConfigException;

class ContractWidget extends Select2
{
    /**
     * @throws ReflectionException
     * @throws InvalidConfigException
     */
    public function run(): void
    {
        $this->data = Contract::find()->hidden()
            ->status([
                Contract::STATUS_WORK,
                Contract::STATUS_PLANNED])
            ->asDropDown();

        parent::run();
    }
}
