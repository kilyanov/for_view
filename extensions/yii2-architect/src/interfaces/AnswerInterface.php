<?php

declare(strict_types=1);

namespace kilyanov\architect\interfaces;

interface AnswerInterface
{
    public const DEFAULT_FORCE_RELOAD = 'js-container-reload';

    public const MODAL_ID = 'js-ajax-modal';

    /**
     * @param string $title
     * @return self
     */
    public function setTitle(string $title): self;

    /**
     * @return string|null
     */
    public function getTitle(): ?string;

    /**
     * @param string $containerReload
     * @return self
     */
    public function setContainerReload(string $containerReload): self;

    /**
     * @return string
     */
    public function getContainerReload(): string;

    /**
     * @return array
     */
    public function isGet(): array;

    /**
     * @return array
     */
    public function isPost(): array;

    /**
     * @return array
     */
    public function isDelete(): array;
}
