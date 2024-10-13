<?php

declare(strict_types=1);

namespace app\modules\industry\modules\order\widgets;

use kilyanov\architect\controller\ApplicationController;
use kilyanov\architect\entity\RowEntity;
use kilyanov\architect\entity\UrlEntity;
use kilyanov\architect\widgets\BaseGroupButtonWidget;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;

class WriteOffNormaButton extends BaseGroupButtonWidget
{
    /**
     * @var string
     */
    public string $active = 'order';

    /**
     * @return string
     * @throws InvalidConfigException
     */
    public function run(): string
    {
        /** @var ApplicationController $controller */
        $controller = Yii::$app->controller;
        $rowEntity = new RowEntity([
            'items' => [
                [
                    'class' => UrlEntity::class,
                    'name' => 'Сбросить',
                    'url' => ArrayHelper::merge(['index'], $this->configUrl),
                    'options' => [
                        'data-pjax' => 0,
                        'class' => 'btn btn-light',
                        'style' => 'margin:0 5px;'
                    ],
                    'access' => ArrayHelper::getValue($this->access, 'index', $controller->getListAccess()),
                    'visible' => ArrayHelper::getValue($this->visible, 'index', true),
                ],
                [
                    'class' => UrlEntity::class,
                    'name' => 'Списание Н/Ч по заказам',
                    'url' => ArrayHelper::merge(['order'], $this->configUrl),
                    'options' => [
                        'data-pjax' => 0,
                        'class' => $this->active === 'order' ? 'btn btn-danger' : 'btn btn-light',
                        'style' => 'margin:0 5px;'
                    ],
                    'access' => ArrayHelper::getValue($this->access, 'order', $controller->getListAccess()),
                    'visible' => ArrayHelper::getValue($this->visible, 'order', true),
                ],
                [
                    'class' => UrlEntity::class,
                    'name' => 'Списание Н/Ч по месяцам',
                    'url' => ArrayHelper::merge(['month'], $this->configUrl),
                    'options' => [
                        'data-pjax' => 0,
                        'class' => $this->active === 'month' ? 'btn btn-danger' : 'btn btn-light',
                        'style' => 'margin:0 5px;'
                    ],
                    'access' => ArrayHelper::getValue($this->access, 'month', $controller->getListAccess()),
                    'visible' => ArrayHelper::getValue($this->visible, 'month', true),
                ],
            ]
        ]);

        return $rowEntity->make();
    }
}
