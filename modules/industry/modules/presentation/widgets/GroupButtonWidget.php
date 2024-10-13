<?php

declare(strict_types=1);

namespace app\modules\industry\modules\presentation\widgets;

use app\modules\industry\models\OrderList;
use Exception;
use kilyanov\architect\controller\ApplicationController;
use kilyanov\architect\entity\RowEntity;
use kilyanov\architect\entity\UrlEntity;
use Yii;
use yii\helpers\ArrayHelper;
use app\widgets\GroupButtonWidget as GroupButtonWidgetAlias;

class GroupButtonWidget extends GroupButtonWidgetAlias
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
                    'name' => 'Ремонт ВВТ',
                    'url' => in_array('create', $this->exceptionConfigUrl) ? ['create'] :
                        ArrayHelper::merge(
                            ['create'],
                            ArrayHelper::merge(
                                $this->configUrl,
                                ['typeOrder' => OrderList::TYPE_PRODUCT]
                            )
                        ),
                    'options' => $this->isAjax ?
                        ['class' => 'btn btn-light', 'role' => 'modal-remote'] :
                        ['class' => 'btn btn-light'],
                    'access' => ArrayHelper::getValue($this->access, 'create', $controller->getListAccess()),
                    'visible' => ArrayHelper::getValue($this->visible, 'create', true),
                ],
                [
                    'class' => UrlEntity::class,
                    'name' => 'Ремонт СИ',
                    'url' => in_array('create', $this->exceptionConfigUrl) ? ['create'] :
                        ArrayHelper::merge(
                            ['create'],
                            ArrayHelper::merge(
                                $this->configUrl,
                                ['typeOrder' => OrderList::TYPE_DEVICE_REPAIR]
                            )
                        ),
                    'options' => $this->isAjax ?
                        ['class' => 'btn btn-light', 'role' => 'modal-remote'] :
                        ['class' => 'btn btn-light'],
                    'access' => ArrayHelper::getValue($this->access, 'create', $controller->getListAccess()),
                    'visible' => ArrayHelper::getValue($this->visible, 'create', true),
                ],
                [
                    'class' => UrlEntity::class,
                    'name' => 'Обслуживание УУТЭ',
                    'url' => in_array('create', $this->exceptionConfigUrl) ? ['create'] :
                        ArrayHelper::merge(
                            ['create'],
                            ArrayHelper::merge(
                                $this->configUrl,
                                ['typeOrder' => OrderList::TYPE_STAND_VERIFICATION]
                            )
                        ),
                    'options' => $this->isAjax ?
                        ['class' => 'btn btn-light', 'role' => 'modal-remote'] :
                        ['class' => 'btn btn-light'],
                    'access' => ArrayHelper::getValue($this->access, 'create', $controller->getListAccess()),
                    'visible' => ArrayHelper::getValue($this->visible, 'create', true),
                ],
                [
                    'class' => UrlEntity::class,
                    'name' => 'Обслуживание НСО',
                    'url' => in_array('create', $this->exceptionConfigUrl) ? ['create'] :
                        ArrayHelper::merge(
                            ['create'],
                            ArrayHelper::merge(
                                $this->configUrl,
                                ['typeOrder' => OrderList::TYPE_STAND]
                            )
                        ),
                    'options' => $this->isAjax ?
                        ['class' => 'btn btn-light', 'role' => 'modal-remote'] :
                        ['class' => 'btn btn-light'],
                    'access' => ArrayHelper::getValue($this->access, 'create', $controller->getListAccess()),
                    'visible' => ArrayHelper::getValue($this->visible, 'create', true),
                ],
                [
                    'class' => UrlEntity::class,
                    'name' => 'Поверка СИ',
                    'url' => in_array('create', $this->exceptionConfigUrl) ? ['create'] :
                        ArrayHelper::merge(
                            ['create'],
                            ArrayHelper::merge(
                                $this->configUrl,
                                ['typeOrder' => OrderList::TYPE_DEVICE_VERIFICATION]
                            )
                        ),
                    'options' => $this->isAjax ?
                        ['class' => 'btn btn-light', 'role' => 'modal-remote'] :
                        ['class' => 'btn btn-light'],
                    'access' => ArrayHelper::getValue($this->access, 'create', $controller->getListAccess()),
                    'visible' => ArrayHelper::getValue($this->visible, 'create', true),
                ],
                [
                    'class' => UrlEntity::class,
                    'name' => 'Экспорт',
                    'url' => ArrayHelper::merge(['export'], $this->configUrl),
                    'options' => ['data-pjax' => 0, 'class' => 'btn btn-success float-end'],
                    'access' => ArrayHelper::getValue($this->access, 'export', $controller->getListAccess()),
                    'visible' => ArrayHelper::getValue($this->visible, 'export', true),
                ],
            ]
        ]);

        return $rowEntity->make();
    }
}
