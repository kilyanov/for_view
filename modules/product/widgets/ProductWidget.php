<?php

declare(strict_types=1);

namespace app\modules\product\widgets;

use app\modules\product\models\Product;
use kartik\select2\Select2;
use ReflectionException;
use yii\base\InvalidConfigException;

class ProductWidget extends Select2
{
    /**
     * @throws ReflectionException
     * @throws InvalidConfigException
     */
    public function run(): void
    {
        $this->data = Product::find()->hidden()->asDropDown();

        parent::run();
    }
}
