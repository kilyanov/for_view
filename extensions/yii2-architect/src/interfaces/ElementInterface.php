<?php

declare(strict_types=1);

namespace kilyanov\architect\interfaces;

interface ElementInterface
{
    /**
     * @param string $template
     * @return self
     */
    public function setTemplate(string $template): self;

    /**
     * @return string|null
     */
    public function getTemplate(): ?string;

    /**
     * @return string|null
     */
    public function getBasePath(): ?string;

    /**
     * @param string $basePath
     * @return self
     */
    public function setBasePath(string $basePath): self;

    /**
     * @param string $name
     * @return self
     */
    public function setName(string $name): self;

    /**
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * @param array $options
     * @return self
     */
    public function setOptions(array $options): self;

    /**
     * @return array|null
     */
    public function getOptions(): ?array;

    /**
     * @param array $access
     * @return self
     */
    public function setAccess(array $access): self;

    /**
     * @return array|null
     */
    public function getAccess(): ?array;

    /**
     * @return bool
     */
    public function isAccess(): bool;

    /**
     * @return bool
     */
    public function isVisible(): bool;

    /**
     * @param bool $visible
     * @return self
     */
    public function setVisible(bool $visible): self;
}
