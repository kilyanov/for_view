<?php

declare(strict_types=1);

namespace kilyanov\architect\entity;

use Yii;
use yii\bootstrap5\Html;

class ContentEntity extends ElementEntity
{
    /**
     * @var array
     */
    private array $params = [];

    /**
     * @var string|null
     */
    private ?string $message = null;

    /**
     * @var array
     */
    private array $messageOptions = [];

    /**
     * @param string $template
     * @return self
     */
    public function setTemplate(string $template): ContentEntity
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTemplate(): ?string
    {
        return $this->template;
    }

    /**
     * @param array $params
     * @return self
     */
    public function setParams(array $params = []): ContentEntity
    {
        $this->params = $params;

        return $this;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @param string $message
     * @return ContentEntity
     */
    public function setMessage(string $message): ContentEntity
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @param array $messageOptions
     * @return $this
     */
    public function setMessageOptions(array $messageOptions): ContentEntity
    {
        $this->messageOptions = $messageOptions;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getMessageOptions(): ?array
    {
        return $this->messageOptions;
    }

    /**
     * @return string
     */
    public function make(): string
    {
        if (!empty($this->template) && $this->message === null) {
            return Yii::$app->controller->renderAjax($this->template, $this->params);
        }
        else {
            return Html::tag(
                'div',
                $this->getMessage(),
                $this->getMessageOptions()
            );
        }
    }
}
