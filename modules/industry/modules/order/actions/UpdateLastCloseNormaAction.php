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

class UpdateLastCloseNormaAction extends BaseAction
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
        $id = (string)$params['id'];
        $model = OrderRationingDataClose::find()
            ->andWhere(['orderRationingDataId' => $id])
            ->orderBy([
                'year' => SORT_DESC,
                'month' => SORT_DESC
            ])
            ->one();
        if ($model === null) {
            throw new NotFoundHttpException(
                "Records OrderRationingDataClose with orderRationingDataId {$id} not found."
            );
        }
        $this->setModel($model);
        $controller->getAnswer()
            ->setTitle('Редактирование последнего списания Н/Ч')
            ->getFooter()->setItems(ButtonCreateFactory::create());
        $controller->getAnswer()
            ->setContainerReload($controller->getForceReload())
            ->getContent()
            ->setTemplate('update-last-close-norma')
            ->setParams([
                'model' => $this->getModel(),
                'listAccess' => $controller->getListAccess(),
            ]);

        return parent::runWithParams($params);
    }
}
