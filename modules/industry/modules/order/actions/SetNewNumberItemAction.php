<?php

declare(strict_types=1);

namespace app\modules\industry\modules\order\actions;

use app\modules\industry\models\OrderRationingData;
use app\modules\industry\modules\order\forms\NewNumberItemForm;
use Exception;
use kilyanov\architect\actions\base\BaseAction;
use kilyanov\architect\controller\ApplicationController;
use kilyanov\architect\factory\ButtonCloseFactory;
use kilyanov\architect\factory\ButtonCreateFactory;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class SetNewNumberItemAction extends BaseAction
{
    /**
     * @return array
     * @throws NotFoundHttpException
     * @throws Exception
     */
    public function run(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        /** @var ApplicationController $controller */
        $controller = $this->controller;
        $request = Yii::$app->request;
        if ($request->isAjax) {
            $params = $request->post('ids') ? explode(',', $request->post('ids')) : [];
            $model = new NewNumberItemForm([
                'orderRationingDataId' => $params,
            ]);
            $this->setModel($model);
            $controller->getAnswer()
                ->setTitle('Установка нового номера пункта')
                ->getFooter()->setItems(ButtonCreateFactory::create());
            $controller->getAnswer()
                ->setContainerReload($controller->getForceReload())
                ->getContent()
                ->setTemplate('new-number-item')
                ->setParams([
                    'model' => $this->getModel(),
                    'listAccess' => $controller->getListAccess(),
                ]);
            if (!empty($params)) {
                return $controller->getAnswer()->isGet();
            }
            if ($request->post() && $this->getModel()->load($request->post())) {
                OrderRationingData::updateAll(
                    ['point' => $this->getModel()->number],
                    ['id' => $this->getModel()->orderRationingDataId]);
                $controller
                    ->getAnswer()
                    ->getContent()
                    ->setMessage('Данные успешно сохранены.')
                    ->setMessageOptions(['class' => 'alert alert-success', 'role' => 'alert']);
                $controller
                    ->getAnswer()
                    ->getFooter()
                    ->setItems(ButtonCloseFactory::create());
            }
            return $controller->getAnswer()->isPost();
        } else {
            throw new NotFoundHttpException('Request is not Ajax.');
        }
    }
}
