<?php

declare(strict_types=1);

namespace app\modules\industry\modules\order\widgets;

use app\modules\industry\models\OrderList;
use Exception;
use kartik\select2\Select2;
use ReflectionException;
use yii\base\InvalidConfigException;

class OrderListWidget extends Select2
{
    /**
     * @throws ReflectionException
     * @throws InvalidConfigException
     * @throws Exception
     */
    public function run()
    {
        $this->data = OrderList::asDropDown(['status' => OrderList::STATUS_OPEN]);

        parent::run();
    }
}
