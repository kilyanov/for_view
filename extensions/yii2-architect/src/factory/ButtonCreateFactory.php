<?php

declare(strict_types=1);

namespace kilyanov\architect\factory;

use kilyanov\architect\entity\ButtonEntity;
use kilyanov\architect\entity\ButtonSubmitEntity;
use kilyanov\architect\entity\ElementEntity;
use kilyanov\architect\interfaces\BaseFactoryInterface;

class ButtonCreateFactory implements BaseFactoryInterface
{
    /**
     * @return array|ElementEntity
     */
    public static function create(): array|ElementEntity
    {
        return [
            new ButtonEntity([
                'name' => 'Закрыть',
                'options' => [
                    'class' => 'btn btn-secondary',
                    'data-bs-dismiss' => 'modal'
                ]
            ]),
            new ButtonSubmitEntity([
                'name' => 'Сохранить',
                'options' => [
                    'class' => 'btn btn-info',
                ]
            ]),
        ];
    }
}
