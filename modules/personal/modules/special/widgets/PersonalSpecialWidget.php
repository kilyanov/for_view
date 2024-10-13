<?php

declare(strict_types=1);

namespace app\modules\personal\modules\special\widgets;

use app\modules\personal\modules\special\models\PersonalSpecial;
use kartik\select2\Select2;
use ReflectionException;
use yii\base\InvalidConfigException;

class PersonalSpecialWidget extends Select2
{
    /**
     * @throws ReflectionException
     * @throws InvalidConfigException
     */
    public function run(): void
    {
        $this->data = PersonalSpecial::asDropDown();

        parent::run();
    }
}
