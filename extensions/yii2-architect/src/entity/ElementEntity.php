<?php

declare(strict_types=1);

namespace kilyanov\architect\entity;

use kilyanov\architect\interfaces\ElementInterface;
use Yii;
use yii\base\BaseObject;

/**
 *
 * @property null|string $template
 * @property-read string $defaultPath
 * @property null|string $basePath
 */
abstract class ElementEntity extends BaseObject implements ElementInterface
{
    /**
     * @var array
     */
    protected array $data = [];

    /**
     * @var string|null
     */
    protected ?string $template = null;

    /**
     * @var string|null
     */
    protected ?string $name = null;

    /**
     * @var array
     */
    protected array $options = [];

    /**
     * @var array
     */
    protected array $access = [];

    /**
     * @var bool
     */
    protected bool $visible = true;

    /**
     * @var string|null
     */
    protected ?string $basePath = null;

    /**
     * @return string
     */
    public function getDefaultPath(): string
    {
        return '@kilyanov/architect/resources/elements';
    }

    /**
     * @param string $template
     * @return self
     */
    public function setTemplate(string $template): ElementInterface
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
     * @return string|null
     */
    public function getBasePath(): ?string
    {
        return $this->basePath;
    }

    /**
     * @param string $basePath
     * @return self
     */
    public function setBasePath(string $basePath): ElementInterface
    {
        $this->basePath = $basePath;

        return $this;
    }

    /**
     * @param string $name
     * @return self
     */
    public function setName(string $name): ElementInterface
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param array $options
     * @return self
     */
    public function setOptions(array $options): ElementInterface
    {
        $this->options = $options;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getOptions(): ?array
    {
        return $this->options;
    }

    /**
     * @param array $access
     * @return self
     */
    public function setAccess(array $access): ElementInterface
    {
        $this->access = $access;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getAccess(): ?array
    {
        return $this->access;
    }

    /**
     * @return bool
     */
    public function isAccess(): bool
    {
        foreach ($this->getAccess() as $access) {
            if (!Yii::$app->user->can($access)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return bool
     */
    public function isVisible(): bool
    {
        return $this->visible;
    }

    /**
     * @param bool $visible
     * @return self
     */
    public function setVisible(bool $visible): ElementInterface
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * @return string
     */
    public function make(): string
    {
        if (!$this->isVisible() || !$this->isAccess()) {
            return '';
        }

        if (($path = $this->getBasePath()) == null) {
            $path = $this->getDefaultPath();
        }

        return Yii::$app->getView()->render($path . '/' . $this->getTemplate(), ['element' => $this]);
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     * @return ElementInterface
     */
    public function setData(array $data): ElementInterface
    {
        $this->data = $data;
        return $this;
    }
}
