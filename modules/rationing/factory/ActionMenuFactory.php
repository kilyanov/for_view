<?php

declare(strict_types=1);

namespace app\modules\rationing\factory;

use kilyanov\architect\entity\DropdownEntity;
use kilyanov\architect\entity\UrlEntity;
use kilyanov\architect\interfaces\BaseActionMenuFactoryInterface;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;

class ActionMenuFactory implements BaseActionMenuFactoryInterface
{
    /**
     * @param array $config
     * @return string
     * @throws InvalidConfigException
     */
    public static function create(array $config = []): string
    {
        $entity = new DropdownEntity([
            'name' => BaseActionMenuFactoryInterface::DEFAULT_ICON,
            'options' => [
                'class' => 'btn btn-default dropdown-toggle',
                'data-bs-toggle' => 'dropdown',
                'aria-expanded' => false,
            ],
            'items' => [
                new UrlEntity([
                    'name' => 'Копировать',
                    'url' => ArrayHelper::merge(['copy'], $config),
                    'options' => [
                        'class' => 'dropdown-item',
                        'title' => 'Копировать',
                        'role' => 'modal-remote'
                    ]
                ]),
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
