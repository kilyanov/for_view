<?php

declare(strict_types=1);

namespace app\modules\impact\widgets;

use app\modules\impact\models\Impact;
use kartik\select2\Select2;
use ReflectionException;
use yii\base\InvalidConfigException;

class ImpactWidget extends Select2
{

    /**
     * @throws ReflectionException
     * @throws InvalidConfigException
     */
    public function run(): void
    {
        /** @var Impact $query */
        $query = Impact::find()->hidden();
        $this->data = $query->order()->asDropDown();

        parent::run();
    }
}
