<?php

declare(strict_types=1);

namespace app\modules\industry\modules\order\widgets;

use Exception;
use kilyanov\architect\controller\ApplicationController;
use kilyanov\architect\entity\RowEntity;
use kilyanov\architect\entity\UrlEntity;
use kilyanov\architect\widgets\BaseGroupButtonWidget;
use Yii;
use yii\helpers\ArrayHelper;

class GroupButtonWidget extends BaseGroupButtonWidget
{
    /**
     * @return string
     * @throws Exception
     */
    public function run(): string
    {
        /** @var ApplicationController $controller */
        $controller = Yii::$app->controller;
        $rowEntity = new RowEntity([
            'items' => [
                [
                    'class' => UrlEntity::class,
                    'name' => 'Общ. сведения по заказу',
                    'url' => ArrayHelper::merge(['/industry/order/default/view'], $this->configUrl),
                    'options' => [
                        'data-pjax' => 0,
                        'class' => $this->active === 'view' ? 'btn btn-danger' : 'btn btn-light',
                        'style' => 'margin-right:5px;'
                    ],
                    'access' => ArrayHelper::getValue($this->access, 'view', $controller->getListAccess()),
                    'visible' => ArrayHelper::getValue($this->visible, 'view', true),
                ],
                [
                    'class' => UrlEntity::class,
                    'name' => 'Изделия',
                    'url' => ArrayHelper::merge(['/industry/order/product/index'], $this->configUrl),
                    'options' => [
                        'data-pjax' => 0,
                        'class' => $this->active === 'product' ? 'btn btn-danger' : 'btn btn-light',
                        'style' => 'margin-right:5px;'
                    ],
                    'access' => ArrayHelper::getValue($this->access, 'index', $controller->getListAccess()),
                    'visible' => ArrayHelper::getValue($this->visible, 'index', true),
                ],
                [
                    'class' => UrlEntity::class,
                    'name' => 'Виды ремонта',
                    'url' => ArrayHelper::merge(['/industry/order/impact/index'], $this->configUrl),
                    'options' => [
                        'data-pjax' => 0,
                        'class' => $this->active === 'impact' ? 'btn btn-danger' : 'btn btn-light',
                        'style' => 'margin-right:5px;'
                    ],
                    'access' => ArrayHelper::getValue($this->access, 'index', $controller->getListAccess()),
                    'visible' => ArrayHelper::getValue($this->visible, 'index', true),
                ],
                [
                    'class' => UrlEntity::class,
                    'name' => 'Подразделения',
                    'url' => ArrayHelper::merge(['/industry/order/unit/index'], $this->configUrl),
                    'options' => [
                        'data-pjax' => 0,
                        'class' => $this->active === 'unit' ? 'btn btn-danger' : 'btn btn-light',
                        'style' => 'margin-right:5px;'
                    ],
                    'access' => ArrayHelper::getValue($this->access, 'index', $controller->getListAccess()),
                    'visible' => ArrayHelper::getValue($this->visible, 'index', true),
                ],
                [
                    'class' => UrlEntity::class,
                    'name' => 'Нормировка',
                    'url' => ArrayHelper::merge(['/industry/order/rationing/index'], $this->configUrl),
                    'options' => [
                        'data-pjax' => 0,
                        'class' => $this->active === 'rationing' ? 'btn btn-danger' : 'btn btn-light',
                        'style' => 'margin-right:5px;'
                    ],
                    'access' => ArrayHelper::getValue($this->access, 'index', $controller->getListAccess()),
                    'visible' => ArrayHelper::getValue($this->visible, 'index', true),
                ],
                [
                    'class' => UrlEntity::class,
                    'name' => 'Обеспечение',
                    'url' => ArrayHelper::merge(['/industry/order/application-data/index'], $this->configUrl),
                    'options' => [
                        'data-pjax' => 0,
                        'class' => $this->active === 'rationing' ? 'btn btn-danger' : 'btn btn-light',
                        'style' => 'margin-right:5px;'
                    ],
                    'access' => ArrayHelper::getValue($this->access, 'index', $controller->getListAccess()),
                    'visible' => ArrayHelper::getValue($this->visible, 'index', true),
                ],
            ]
        ]);

        return $rowEntity->make();
    }
}
