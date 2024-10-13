<?php

declare(strict_types=1);

namespace kilyanov\architect\actions\base;

use kilyanov\architect\controller\ApplicationController;
use kilyanov\architect\factory\ButtonCreateFactory;
use Yii;
use yii\base\InvalidConfigException;
use yii\web\NotFoundHttpException;

class CreateAction extends BaseAction
{
    /**
     * @throws NotFoundHttpException
     */
    public function run(): array
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            return $this->getAnswer();
        } else {
            throw new NotFoundHttpException('Request is not Ajax.');
        }
    }

    /**
     * @return bool
     */
    public function beforeRun(): bool
    {
        /** @var ApplicationController $controller */
        $controller = $this->controller;
        $modelClass = $controller->getModelClass();
        $this->setModel((new $modelClass($controller->getCfgModel())));
        $controller->getAnswer()
            ->setTitle('Добавление записи')
            ->getFooter()->setItems(ButtonCreateFactory::create());
        $controller->getAnswer()
            ->setContainerReload($controller->getForceReload())
            ->getContent()
            ->setTemplate('create')
            ->setParams([
                'model' => $this->getModel(),
                'listAccess' => $controller->getListAccess(),
            ]);

        return true;
    }

    /**
     * @param $params
     * @return mixed|null
     * @throws InvalidConfigException
     */
    public function runWithParams($params): mixed
    {
        return parent::runWithParams($params);
    }
}
