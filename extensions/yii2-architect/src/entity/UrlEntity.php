<?php

declare(strict_types=1);

namespace kilyanov\architect\entity;

use kilyanov\architect\interfaces\UrlInterface;
use Yii;
use yii\base\InvalidArgumentException;
use yii\helpers\ArrayHelper;

class UrlEntity extends ElementEntity implements UrlInterface
{
    /**
     * @var string|null
     */
    protected ?string $template = 'url';

    /**
     * @var array|string|null
     */
    protected array|string|null $url = null;

    /**
     * @var string
     */
    protected string $target = UrlInterface::TARGET_SELF;

    /**
     * @var int
     */
    protected int $absoluteUrl = UrlInterface::ABSOLUTE_LINK_NO;

    /**
     * @param string $target
     * @return self
     */
    public function setTarget(string $target): self
    {
        $this->target = $target;

        return $this;
    }

    /**
     * @return string
     */
    public function getTarget(): string
    {
        return $this->target;
    }

    /**
     * @param array|string $url
     * @return UrlInterface
     */
    public function setUrl(array|string $url): UrlInterface
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return array|string|null
     */
    public function getUrl(): null|array|string
    {
        return $this->url;
    }

    /**
     * @param int $absoluteUrl
     * @return self
     */
    public function setAbsoluteUrl(int $absoluteUrl): self
    {
        $this->absoluteUrl = $absoluteUrl;

        return $this;
    }

    /**
     * @return int
     */
    public function getAbsoluteUrl(): int
    {
        return $this->absoluteUrl;
    }

    /**
     * @return bool
     */
    public function isAbsoluteUrl(): bool
    {
        return $this->absoluteUrl === UrlInterface::ABSOLUTE_LINK_YES;
    }

    /**
     * @return string
     */
    public function make(): string
    {
        if ($this->getUrl() === null) {
            throw new InvalidArgumentException("Property url not set.");
        }

        $this->setOptions(ArrayHelper::merge($this->getOptions(), ['target' => $this->getTarget()]));

        if ($this->isAbsoluteUrl()) {
            $this->setUrl(Yii::$app->getUrlManager()->createAbsoluteUrl($this->getUrl(), true));
        }

        return parent::make();
    }
}
