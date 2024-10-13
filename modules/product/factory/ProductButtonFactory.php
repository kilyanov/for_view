<?php

declare(strict_types=1);

namespace app\modules\product\factory;

use kilyanov\architect\entity\ElementEntity;
use kilyanov\architect\entity\RowEntity;
use kilyanov\architect\entity\UrlEntity;
use kilyanov\architect\interfaces\BaseFactoryInterface;

class ProductButtonFactory implements BaseFactoryInterface
{
    /**
     * @param array $config
     * @return array|ElementEntity
     */
    public static function create(array $config = []): array|ElementEntity
    {
        return new RowEntity([
            'items' => [
                new UrlEntity([
                    'name' => 'Изделия',
                    'url' => ['/product/default'],
                    'options' => [
                        'class' => 'btn btn-info',
                        'title' => 'Изделия',
                    ]
                ]),
                new UrlEntity([
                    'name' => 'Узлы, системы',
                    'url' => ['/product/node'],
                    'options' => [
                        'class' => 'btn btn-info',
                        'title' => 'Узлы, системы',
                    ]
                ]),
                new UrlEntity([
                    'name' => 'Блоки',
                    'url' => ['/product/block'],
                    'options' => [
                        'class' => 'btn btn-info',
                        'title' => 'Блоки',
                    ]
                ]),
            ]
        ]);
    }
}
