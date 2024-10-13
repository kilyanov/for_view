<?php

declare(strict_types=1);

namespace app\modules\rationing\widgets;

use app\modules\rationing\models\RationingProduct;
use Exception;
use kartik\select2\Select2;
use ReflectionException;
use yii\base\InvalidConfigException;

class RationingProductWidget extends Select2
{
    /**
     * @throws ReflectionException
     * @throws InvalidConfigException
     * @throws Exception
     */
    public function run()
    {
        $this->data = RationingProduct::asDropDown();

        parent::run();
    }
}
