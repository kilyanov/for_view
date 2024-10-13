<?php

declare(strict_types=1);

namespace app\modules\industry\modules\order\actions;

use app\modules\industry\models\OrderRationingDataClose;
use kilyanov\architect\actions\base\BaseAction;
use kilyanov\architect\controller\ApplicationController;
use kilyanov\architect\factory\ButtonCreateFactory;
use Yii;
use yii\base\InvalidConfigException;
use yii\web\NotFoundHttpException;

class CloseNormaAction extends BaseAction
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
     * @param $params
     * @return mixed
     * @throws InvalidConfigException
     * @throws NotFoundHttpException
     */
    public function runWithParams($params): mixed
    {
        /** @var ApplicationController $controller */
        $controller = $this->controller;
        $model = $controller->findModel((string)$params['id']);
        $createModel = new OrderRationingDataClose([
            'orderRationingDataId' => $model->id,
            'year' => date('Y'),
            'norma' => $model->stayNorma,
            'month' => date('m'),
        ]);
        $this->setModel($createModel);
        $controller->getAnswer()
            ->setTitle('Закрытие Н/Ч')
            ->getFooter()->setItems(ButtonCreateFactory::create());
        $controller->getAnswer()
            ->setContainerReload($controller->getForceReload())
            ->getContent()
            ->setTemplate('close-norma')
            ->setParams([
                'model' => $this->getModel(),
                'listAccess' => $controller->getListAccess(),
            ]);

        return parent::runWithParams($params);
    }
}
