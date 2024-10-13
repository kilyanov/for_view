<?php

declare(strict_types=1);

namespace kilyanov\architect\factory;

use kilyanov\architect\entity\DropdownEntity;
use kilyanov\architect\entity\UrlEntity;
use kilyanov\architect\interfaces\BaseActionMenuFactoryInterface;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;

class BaseActionMenuFactory implements BaseActionMenuFactoryInterface
{
    /**
     * @param array $config
     * @return string
     * @throws InvalidConfigException
     */
    public static function create(array $config = []): string
    {
        $entity = new DropdownEntity([
            'name' => self::DEFAULT_ICON,
            'options' => [
                'class' => 'btn btn-default dropdown-toggle',
                'data-bs-toggle' => 'dropdown',
                'aria-expanded' => false,
            ],
            'items' => [
                new UrlEntity([
                    'name' => 'Редактировать',
                    'url' => ArrayHelper::merge(['update'], $config),
                    'options' => [
                        'class' => 'dropdown-item',
                        'title' => 'Редактировать',
                        'role' => 'modal-remote'
                    ]
                ]),
                new UrlEntity([
                    'name' => 'Удалить',
                    'url' => ArrayHelper::merge(['delete'], $config),
                    'options' => [
                        'class' => 'dropdown-item',
                        'title' => 'Удалить',
                        'role' => 'modal-remote',
                        'data-confirm-title' => 'Подтверждение удаления!',
                        'data-confirm-message' => 'Вы уверены что хотите удалить запись?',
                    ]
                ]),
            ]
        ]);

        return $entity->make();
    }
}
