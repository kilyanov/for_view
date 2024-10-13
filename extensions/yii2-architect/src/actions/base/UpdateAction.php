<?php

declare(strict_types=1);

namespace kilyanov\architect\actions\base;

use kilyanov\architect\controller\ApplicationController;
use kilyanov\architect\factory\ButtonCreateFactory;
use Yii;
use yii\base\InvalidConfigException;
use yii\web\NotFoundHttpException;

class UpdateAction extends BaseAction
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
     * @throws InvalidConfigException
     * @throws NotFoundHttpException
     */
    public function runWithParams($params)
    {
        /** @var ApplicationController $controller */
        $controller = $this->controller;
        $model = $controller->findModel((string)$params['id']);
        if (!empty($controller->getCfgModel())) {
            $model->setAttributes($controller->getCfgModel());
        }
        $this->setModel($model);
        $controller->getAnswer()
            ->setTitle('Редактирование записи')
            ->getFooter()->setItems(ButtonCreateFactory::create());
        $controller->getAnswer()
            ->setContainerReload($controller->getForceReload())
            ->getContent()
            ->setTemplate('update')
            ->setParams([
                'model' => $this->getModel(),
                'listAccess' => $controller->getListAccess(),
            ]);

        return parent::runWithParams($params);
    }
}
