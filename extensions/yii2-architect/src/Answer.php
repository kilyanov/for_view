<?php

declare(strict_types=1);

namespace kilyanov\architect;

use kilyanov\architect\entity\ContentEntity;
use kilyanov\architect\entity\RowEntity;
use kilyanov\architect\interfaces\AnswerInterface;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;

class Answer extends BaseObject implements AnswerInterface
{
    /**
     * @var string|null
     */
    private ?string $title = null;

    /**
     * @var string
     */
    private string $containerReload = AnswerInterface::DEFAULT_FORCE_RELOAD;

    /**
     * @var ContentEntity|null
     */
    private ?ContentEntity $content = null;

    /**
     * @var RowEntity|null
     */
    private ?RowEntity $footer = null;

    /**
     * @param string $title
     * @return self
     */
    public function setTitle(string $title): AnswerInterface
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $containerReload
     * @return self
     */
    public function setContainerReload(string $containerReload): AnswerInterface
    {
        $this->containerReload = $containerReload;

        return $this;
    }

    /**
     * @return string
     */
    public function getContainerReload(): string
    {
        return $this->containerReload;
    }

    /**
     * @return array
     * @throws InvalidConfigException
     */
    public function isGet(): array
    {
        return [
            'title' => $this->getTitle(),
            'content' => $this->getContent()->make(),
            'footer' => $this->getFooter()->make(),
        ];
    }

    /**
     * @return array
     * @throws InvalidConfigException
     */
    public function isPost(): array
    {
        return [
            'forceReload' => '#' . $this->getContainerReload(),
            'title' => $this->getTitle(),
            'content' => $this->getContent()->make(),
            'footer' => $this->getFooter()->make(),
        ];
    }

    /**
     * @return array
     */
    public function isDelete(): array
    {
        return [
            'forceClose' => true,
            'forceReload' => '#' . $this->getContainerReload()
        ];
    }

    /**
     * @return ContentEntity|null
     */
    public function getContent(): ?ContentEntity
    {
        return $this->content;
    }

    /**
     * @return RowEntity|null
     */
    public function getFooter(): ?RowEntity
    {
        return $this->footer;
    }

    /**
     * @param ContentEntity|array|null $content
     * @return $this
     */
    public function setContent(ContentEntity|array|null $content): Answer
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @param RowEntity|array|null $footer
     * @return $this
     */
    public function setFooter(RowEntity|array|null $footer): Answer
    {
        $this->footer = $footer;
        return $this;
    }
}
