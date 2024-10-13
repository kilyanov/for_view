<?php

declare(strict_types=1);

namespace app\widgets;

use Exception;
use kilyanov\architect\controller\ApplicationController;
use kilyanov\architect\entity\RowEntity;
use kilyanov\architect\entity\UrlEntity;
use Yii;
use yii\bootstrap5\Widget;
use yii\helpers\ArrayHelper;

class GroupButtonWidget extends Widget
{
    /**
     * @var bool
     */
    public bool $isAjax = true;

    /**
     * @var array
     */
    public array $access = [];

    /**
     * @var array
     */
    public array $visible = [];

    /**
     * @var array
     */
    public array $configUrl = [];

    /**
     * @var array
     */
    public array $exceptionConfigUrl = [];

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
                    'name' => 'Добавить',
                    'url' => in_array('create', $this->exceptionConfigUrl) ? ['create'] :
                        ArrayHelper::merge(['create'], $this->configUrl),
                    'options' => $this->isAjax ?
                        ['class' => 'btn btn-primary', 'role' => 'modal-remote'] :
                        ['class' => 'btn btn-primary'],
                    'access' => ArrayHelper::getValue($this->access, 'create', $controller->getListAccess()),
                    'visible' => ArrayHelper::getValue($this->visible, 'create', true),
                ],
                [
                    'class' => UrlEntity::class,
                    'name' => 'Сбросить',
                    'url' => in_array('index', $this->exceptionConfigUrl) ? ['index'] :
                        ArrayHelper::merge(['index'], $this->configUrl),
                    'options' => ['class' => 'btn btn-secondary'],
                    'access' => ArrayHelper::getValue($this->access, 'index', $controller->getListAccess()),
                    'visible' => ArrayHelper::getValue($this->visible, 'index', true),
                ],
                [
                    'class' => UrlEntity::class,
                    'name' => 'Перемещение',
                    'url' => in_array('move', $this->exceptionConfigUrl) ? ['move'] :
                        ArrayHelper::merge(['move'], $this->configUrl),
                    'options' => ['class' => 'btn btn-warning'],
                    'access' => ArrayHelper::getValue($this->access, 'move', $controller->getListAccess()),
                    'visible' => ArrayHelper::getValue($this->visible, 'move', true),
                ],
                [
                    'class' => UrlEntity::class,
                    'name' => 'Копировать',
                    'url' => ArrayHelper::merge(['copy'], $this->configUrl),
                    'options' => $this->isAjax ?
                        ['class' => 'btn btn-success', 'role' => 'modal-remote'] :
                        ['class' => 'btn btn-success'],
                    'access' => ArrayHelper::getValue($this->access, 'copy', $controller->getListAccess()),
                    'visible' => ArrayHelper::getValue($this->visible, 'copy', false),
                ],
                [
                    'class' => UrlEntity::class,
                    'name' => 'Импорт',
                    'url' => ArrayHelper::merge(['import'], $this->configUrl),
                    'options' => $this->isAjax ?
                        ['class' => 'btn btn-success', 'role' => 'modal-remote'] :
                        ['class' => 'btn btn-success'],
                    'access' => ArrayHelper::getValue($this->access, 'import', $controller->getListAccess()),
                    'visible' => ArrayHelper::getValue($this->visible, 'import', false),
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
