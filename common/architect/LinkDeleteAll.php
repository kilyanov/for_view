<?php

namespace app\common\architect;

use kilyanov\architect\entity\UrlEntity;

class LinkDeleteAll extends UrlEntity
{
    /**
     * @var string|null
     */
    protected ?string $name = 'Удалить выбранные';

    /**
     * @var array|string|null
     */
    protected array|string|null $url = ['delete-all'];

    /**
     * @var array
     */
    protected array $options = [
        'class' => 'btn btn-danger',
        'role' => 'modal-remote-bulk',
        'data-confirm' => false,
        'data-method' => false,
        'data-request-method' => 'post',
        'data-confirm-title' => 'Подтверждение удаления!',
        'data-confirm-message' => 'Вы уверены что хотите удалить выбранные записи?'
    ];
}
