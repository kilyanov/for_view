<?php

declare(strict_types=1);

namespace kilyanov\architect\actions\base;

use kilyanov\architect\controller\ApplicationController;
use Yii;

class IndexAction extends BaseAction
{
    /**
     * @return void
     */
    public function init(): void
    {
        $this->setTemplate('index');
    }

    /**
     * @return string
     */
    public function run(): string
    {
        /** @var ApplicationController $controller */
        $controller = $this->controller;
        $searchClass = $controller->getSearchModelClass();
        $model = new $searchClass($controller->getCfgSearchModel());
        return $controller->{$controller->getTypeRender()}(
            $this->getTemplate(),
            [
                'model' => $model,
                'dataProvider' => $model->search(Yii::$app->request->queryParams),
                'forceReload' => $controller->getForceReload(),
                'listAccess' => $controller->getListAccess(),
            ]
        );
    }
}
