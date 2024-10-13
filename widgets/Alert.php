<?php

declare(strict_types=1);

namespace app\widgets;

use Throwable;
use Yii;
use yii\bootstrap5\Widget;
use yii\bootstrap5\Alert as AlertAlias;

class Alert extends Widget
{
    /**
     * @var array|string[]
     */
    public array $alertTypes = [
        'error' => 'alert-danger',
        'danger' => 'alert-danger',
        'success' => 'alert-success',
        'info' => 'alert-info',
        'warning' => 'alert-warning'
    ];

    /**
     * @var array
     */
    public array $closeButton = [];

    /**
     * @return void
     * @throws Throwable
     */
    public function run(): void
    {
        $session = Yii::$app->session;
        $appendClass = isset($this->options['class']) ? ' ' . $this->options['class'] : '';

        foreach (array_keys($this->alertTypes) as $type) {
            $flash = $session->getFlash($type);

            foreach ((array)$flash as $i => $message) {
                echo AlertAlias::widget([
                    'body' => $message,
                    'closeButton' => $this->closeButton,
                    'options' => array_merge($this->options, [
                        'id' => $this->getId() . '-' . $type . '-' . $i,
                        'class' => $this->alertTypes[$type] . $appendClass,
                    ]),
                ]);
            }

            $session->removeFlash($type);
        }
    }
}
