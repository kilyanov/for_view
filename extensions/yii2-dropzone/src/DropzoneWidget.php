<?php

declare(strict_types=1);

namespace kilyanov\dropzone;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\JsExpression;
use yii\web\Request;
use yii\web\View;

class DropzoneWidget extends Widget
{
    /**
     * @var array
     */
    public array $options = [
        'class' => 'dropzone',
    ];

    /**
     * @var array
     */
    public array $clientOptions = [];

    /**
     * @var array
     */
    public array $clientEvents = [];

    /**
     * @var bool|string
     */
    public bool|string $autoDiscover = false;

    /**
     * @return void
     */
    public function init(): void
    {
        parent::init();

        $this->autoDiscover = $this->autoDiscover === false ? 'false' : 'true';

        if (Yii::$app->getRequest()->enableCsrfValidation) {
            $this->clientOptions['headers'][Request::CSRF_HEADER] = Yii::$app->getRequest()->getCsrfToken();
            $this->clientOptions['params'][Yii::$app->getRequest()->csrfParam] = Yii::$app->getRequest()->getCsrfToken();
        }

        $this->options = array_merge([
            'id' => $this->getId(),
            'class' => 'dropzone',
        ], $this->options);

        $this->setId($this->options['id']);
    }

    /**
     * @return string
     */
    public function run(): string
    {
        $this->registerAsset();
        $this->registerClientOptions();
        $this->registerClientEvents();

        return Html::tag('div', '', $this->options);
    }

    /**
     * @return void
     */
    protected function registerAsset(): void
    {
        DropzoneAsset::register($this->getView());
    }

    /**
     * @return void
     */
    protected function registerClientOptions(): void
    {
        $this->getView()->registerJs(new JsExpression('Dropzone.autoDiscover = ' . $this->autoDiscover . ';'), View::POS_END);
        $this->getView()->registerJs(new JsExpression('Dropzone.confirm = function(question, accepted, rejected) { yii.confirm(question, accepted, rejected); }'), View::POS_END);

        $this->getView()->registerJs(new JsExpression('let ' . $this->getId() . ' = new Dropzone("#' . $this->getId() . '", ' . Json::encode($this->clientOptions) . ');'));
    }

    /**
     * @return void
     */
    protected function registerClientEvents(): void
    {
        if (!empty($this->clientEvents)) {
            $js = [];

            foreach ($this->clientEvents as $event => $handler) {
                $js[] = new JsExpression($this->getId() . '.on("' . $event . '", ' . $handler . ');');
            }

            $this->getView()->registerJs(new JsExpression(implode(PHP_EOL, $js)));
        }
    }
}
