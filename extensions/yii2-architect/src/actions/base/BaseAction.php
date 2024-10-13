<?php

declare(strict_types=1);

namespace kilyanov\architect\actions\base;

use Exception;
use kilyanov\architect\controller\ApplicationController;
use kilyanov\architect\factory\ButtonCloseFactory;
use Yii;
use yii\base\Action;
use yii\base\Model;
use yii\web\Response;

/**
 *
 * @property-read array|string[] $answer
 */
class BaseAction extends Action
{
    /**
     * @var string
     */
    protected string $template = '';

    /**
     * @var Model|null
     */
    private ?Model $model = null;

    /**
     * @return string
     */
    public function getTemplate(): string
    {
        return $this->template;
    }

    /**
     * @param string $template
     */
    public function setTemplate(string $template): void
    {
        $this->template = $template;
    }

    /**
     * @return Model|null
     */
    public function getModel(): ?Model
    {
        return $this->model;
    }

    /**
     * @param Model|null $model
     * @return void
     */
    public function setModel(?Model $model): void
    {
        $this->model = $model;
    }

    /**
     * @return void
     */
    protected function setErrorSaveData(): void
    {
        /** @var ApplicationController $controller */
        $controller = $this->controller;
        $controller
            ->getAnswer()
            ->getContent()
            ->setMessage(implode(', ', $this->getModel()->getErrorSummary(true)))
            ->setMessageOptions(['class' => 'alert alert-danger', 'role' => 'alert']);
        $controller
            ->getAnswer()
            ->getFooter()
            ->setItems(ButtonCloseFactory::create());
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
}
