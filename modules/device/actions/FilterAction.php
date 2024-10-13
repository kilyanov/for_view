<?php

declare(strict_types=1);

namespace app\modules\device\actions;

use app\modules\device\factory\CollectionButtonFilterFactory;
use app\modules\device\forms\FilterDeviceForm;
use kilyanov\architect\actions\base\BaseAction;
use kilyanov\architect\controller\ApplicationController;
use Yii;
use yii\base\InvalidConfigException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class FilterAction extends BaseAction
{
    /**
     * @throws NotFoundHttpException
     */
    public function run(): array|Response
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            /** @var ApplicationController $controller */
            $controller = $this->controller;
            Yii::$app->response->format = Response::FORMAT_JSON;
            if (Yii::$app->request->isGet) {
                return $controller->getAnswer()->isGet();
            } else if ($this->getModel()->load(Yii::$app->request->post())
                && $this->getModel()->validate()) {
                return $controller->redirect($this->getModel()->getUrlRedirect());
            }
            $this->setErrorSaveData();

            return $controller->getAnswer()->isPost();
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
        $this->setModel((new FilterDeviceForm()));
        $controller->getAnswer()
            ->setTitle('Поиск записей')
            ->getFooter()->setItems(CollectionButtonFilterFactory::create());
        $controller->getAnswer()
            ->setContainerReload($controller->getForceReload())
            ->getContent()
            ->setTemplate('filter')
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
