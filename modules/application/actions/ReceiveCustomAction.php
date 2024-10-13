<?php

declare(strict_types=1);

namespace app\modules\application\actions;

use app\modules\application\models\ApplicationData;
use Exception;
use kilyanov\architect\actions\base\BaseAction;
use kilyanov\architect\controller\ApplicationController;
use kilyanov\architect\factory\ButtonCloseFactory;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ReceiveCustomAction extends BaseAction
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
            if (!empty($params)) {
                $models = ApplicationData::find()
                    ->ids($params)
                    ->all();
                foreach ($models as $item) {
                    /** @var ApplicationData $item */
                    $item->quantityReceipt = $item->quantity;
                    $item->save();
                }
            }

            $controller
                ->getAnswer()
                ->getContent()
                ->setMessage('Данные успешно сохранены.')
                ->setMessageOptions(['class' => 'alert alert-success', 'role' => 'alert']);
            $controller
                ->getAnswer()
                ->getFooter()
                ->setItems(ButtonCloseFactory::create());
            return $controller->getAnswer()->isPost();
        } else {
            throw new NotFoundHttpException('Request is not Ajax.');
        }
    }
}
