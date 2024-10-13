<?php

declare(strict_types=1);

namespace kilyanov\architect\actions\base;

use Exception;
use kilyanov\architect\controller\ApplicationController;
use kilyanov\architect\factory\ButtonCloseFactory;
use kilyanov\architect\factory\ButtonCreateFactory;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class CopyAction extends BaseAction
{
    /**
     * @var Model|null
     */
    public ?Model $copyModel;

    /**
     * @var array
     */
    public array $copyRelations = [];

    /**
     * @var string|null
     */
    public ?string $targetAttribute = null;

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
        $modelClass = $controller->getModelClass();
        $createModel = new $modelClass();
        if ($createModel instanceof ActiveRecord) {
            $this->copyModel = $model = $controller->findModel((string)$params['id']);
            $attributes = $model->getAttributes();
            unset($attributes['id']);
            $createModel->setAttributes($attributes);

        }

        $this->setModel($createModel);
        $controller->getAnswer()
            ->setTitle('Копирование записи')
            ->getFooter()->setItems(ButtonCreateFactory::create());
        $controller->getAnswer()
            ->setContainerReload($controller->getForceReload())
            ->getContent()
            ->setTemplate('copy')
            ->setParams([
                'model' => $this->getModel(),
                'listAccess' => $controller->getListAccess(),
            ]);

        return parent::runWithParams($params);
    }

    /**
     * @return array|string[]
     */
    protected function getAnswer(): array
    {
        /** @var ApplicationController $controller */
        $controller = $this->controller;
        Yii::$app->response->format = Response::FORMAT_JSON;
        try {
            if (Yii::$app->request->isGet) {
                return $controller->getAnswer()->isGet();
            } else if ($this->getModel()->load(Yii::$app->request->post())
                && $this->getModel()->validate()) {
                if ($this->getModel()->save()) {
                    $this->copyRelation($this->getModel());
                    $controller
                        ->getAnswer()
                        ->getContent()
                        ->setMessage('Данные успешно сохранены.')
                        ->setMessageOptions(['class' => 'alert alert-success', 'role' => 'alert']);
                    $controller
                        ->getAnswer()
                        ->getFooter()
                        ->setItems(ButtonCloseFactory::create());
                } else {
                    $this->setErrorSaveData();
                }

                return $controller->getAnswer()->isPost();
            }
            $this->setErrorSaveData();

            return $controller->getAnswer()->isPost();
        }
        catch (Exception $exception) {
            return [
                'forceReload' => '#' . $controller->getAnswer()->getContainerReload(),
                'title' => $controller->getAnswer()->getTitle(),
                'content' => $exception->getMessage(),
                'footer' => $controller->getAnswer()->getFooter()->make(),
            ];
        }
    }

    /**
     * n void
     */
    protected function copyRelation(Model $model): void
    {
        if (!empty($this->copyRelations)) {
            foreach ($this->copyRelations as $relation) {
                if ($this->copyModel->$relation) {
                    foreach ($this->copyModel->$relation as $item) {
                        $className = get_class($item);
                        $addModel = new $className($item);
                        $addModel->{$this->targetAttribute} = $model->id;
                        $addModel->save();
                    }
                }
            }
        }
    }
}
