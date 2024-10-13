<?php

declare(strict_types=1);

namespace kilyanov\architect\interfaces;

interface ImportInterface
{
    public const TYPE_EXIST = 'exist';

    public const TYPE_BEFORE_IMPORT = 'before_import';

    public const TYPE_STRING = 'string';

    public const TYPE_INTEGER = 'integer';

    public const TYPE_FLOAT = 'float';

    public const TYPE_CLOSURE = 'closure';

    public const FILE_EXTENSIONS = [
        'xls', 'xlsx'
    ];

    public const DIR_IMPORT_FILE = '@runtime/import';

    /**
     * @param array $data
     * @param array $row
     * @param string $columnAddress
     * @return array
     */
    public function getDataValue(array $data, array $row, string $columnAddress): array;

    /**
     * @return array
     */
    public function readFileImport(): array;

    /**
     * @return bool
     */
    public function importData(): bool;

    /**
     * @return bool
     */
    public function save(): bool;

    /**
     * @return string
     */
    public function getImportModel(): string;

    /**
     * @return array
     */
    public function getImportMap(): array;
}
