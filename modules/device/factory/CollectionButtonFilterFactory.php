<?php

declare(strict_types=1);

namespace app\modules\device\factory;
use kilyanov\architect\entity\ButtonEntity;
use kilyanov\architect\entity\ButtonSubmitEntity;
use kilyanov\architect\entity\ElementEntity;
use kilyanov\architect\interfaces\BaseFactoryInterface;

class CollectionButtonFilterFactory implements BaseFactoryInterface
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
                'name' => 'Поиск',
                'options' => [
                    'type' => 'submit',
                    'class' => 'btn btn-info',
                    'data-bs-dismiss' => 'modal'
                ]
            ]),
        ];
    }
}
