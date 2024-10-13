<?php

declare(strict_types=1);

namespace app\modules\device\architect;

use kilyanov\architect\entity\UrlEntity;

class FilterButton extends UrlEntity
{
    /**
     * @var string|null
     */
    protected ?string $name = 'Фильтр';

    /**
     * @var array
     */
    protected array $options = [
        'class' => 'btn btn-success',
        'role' => 'modal-remote'
    ];
}
