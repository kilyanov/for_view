<?php

declare(strict_types=1);

namespace kilyanov\architect\controller;

interface ControllerInterface
{
    /**
     * @return array
     */
    public function getExportAttribute(): array;

    /**
     * @return string
     */
    public function getImportModel(): string;
}
