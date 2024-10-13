<?php

declare(strict_types=1);

namespace kilyanov\architect\actions\base;

use Exception;
use kilyanov\architect\controller\ApplicationController;
use kilyanov\architect\factory\ButtonCloseFactory;
use kilyanov\architect\factory\ButtonCreateFactory;
use kilyanov\architect\interfaces\ImportInterface;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ImportAction extends BaseAction
{
    /**
     * @var array
     */
    public array $fileMap = [];

    /**
     * @var string|null
     */
    public ?string $importModel = null;

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
     * @throws NotFoundHttpException
     */
    public function beforeRun(): bool
    {
        if (empty($this->fileMap)) {
            throw new NotFoundHttpException('Не определён файл настроек импорта.');
        }
        /** @var ApplicationController $controller */
        $controller = $this->controller;
        $modelClass = $controller->getImportModel();
        $cfgModel = ArrayHelper::merge(
            [
                'fileMap' => $this->fileMap,
                'importModel' => $this->importModel
            ],
            [
                'cfgParams' => $controller->getCfgModel()
            ]
        );
        $this->setModel((new $modelClass($cfgModel)));
        if (!($this->getModel() instanceof ImportInterface)) {
            throw new NotFoundHttpException('Модель импорта не соответствует интерфейсу ImportInterface.');
        }
        $controller->getAnswer()
            ->setTitle('Импорт данных')
            ->getFooter()->setItems(ButtonCreateFactory::create());
        $controller->getAnswer()
            ->setContainerReload($controller->getForceReload())
            ->getContent()
            ->setTemplate('import')
            ->setParams([
                'model' => $this->getModel(),
                'listAccess' => $controller->getListAccess(),
            ]);

        return true;
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
            } else if ($this->getModel()->load(Yii::$app->request->post())) {
                if ($this->getModel()->save()) {
                    $message = 'Всего элементов: ' . $this->getModel()->getRowAll() . ', пропущено элементов: ' .
                        $this->getModel()->getRowExist() . ', добавлено элементов: ' . $this->getModel()->getRowSuccess();
                    $controller
                        ->getAnswer()
                        ->getContent()
                        ->setMessage($message)
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
        } catch (Exception $exception) {
            return [
                'forceReload' => '#' . $controller->getAnswer()->getContainerReload(),
                'title' => $controller->getAnswer()->getTitle(),
                'content' => $exception->getMessage(),
                'footer' => $controller->getAnswer()->getFooter()->make(),
            ];
        }
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
