<?php

declare(strict_types=1);

namespace kilyanov\architect\interfaces;

interface UrlInterface
{
    public const ABSOLUTE_LINK_NO = 0;
    public const ABSOLUTE_LINK_YES = 1;

    public const TARGET_SELF = '_self';
    public const TARGET_BLANK = '_blank';

    /**
     * @param array|string $url
     * @return self
     */
    public function setUrl(array|string $url): self;

    /**
     * @return array|string|null
     */
    public function getUrl(): null|array|string;

    /**
     * @param string $target
     * @return self
     */
    public function setTarget(string $target): self;

    /**
     * @return string
     */
    public function getTarget(): string;

    /**
     * @param int $absoluteUrl
     * @return self
     */
    public function setAbsoluteUrl(int $absoluteUrl): self;

    /**
     * @return int
     */
    public function getAbsoluteUrl(): int;

    /**
     * @return bool
     */
    public function isAbsoluteUrl(): bool;
}
