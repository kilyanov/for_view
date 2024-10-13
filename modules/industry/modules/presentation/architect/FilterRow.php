<?php

namespace app\modules\device\architect;

use Exception;
use kilyanov\architect\entity\DropdownEntity;
use kilyanov\architect\entity\RowEntity;
use kilyanov\architect\entity\UrlEntity;
use yii\helpers\ArrayHelper;

class FilterRow extends RowEntity
{
    /**
     * @var string
     */
    public string $modelName = 'DeviceSearch';

    /**
     * @var string
     */
    public string $baseUrl = '/device/default/index';

    /**
     * @throws Exception
     */
    public function init(): void
    {
        $countInPage = ArrayHelper::getValue($this->getData(), 'pageLimitValue') !== null
            ? ': ' . ArrayHelper::getValue($this->getData(), 'pageLimitValue') : '';
        $state = ArrayHelper::getValue($this->getData(), 'statusValue') != null ? ': ' .
            ArrayHelper::getValue($this->getData(), 'statusValue') : '';
        $group = ArrayHelper::getValue($this->getData(), 'deviceGroupValue') != null ? ': ' .
            ArrayHelper::getValue($this->getData(), 'deviceGroupValue') : '';
        $items = [
            new UrlEntity([
                'name' => 'Эталоны',
                'url' => [
                    $this->baseUrl,
                    $this->modelName . '[status]' => ArrayHelper::getValue($this->getData(), 'status'),
                    $this->modelName . '[pageLimit]' => ArrayHelper::getValue($this->getData(), 'pageLimit', 20),
                    $this->modelName . '[standardShow]' => (int)ArrayHelper::getValue($this->getData(), 'standardShow') === 1 ? 0 : 1,
                    $this->modelName . '[deviceGroupId]' => ArrayHelper::getValue($this->getData(), 'deviceGroupId'),
                ],
                'options' => [
                    'class' => (int)ArrayHelper::getValue($this->getData(), 'standardShow') === 1 ? 'btn btn-secondary' : 'btn btn-light',
                    'data-pjax' => 0,
                ]
            ]),
            new DropdownEntity([
                'name' => 'Кол-во на страницу' . $countInPage,
                'options' => [
                    'class' => 'btn btn-light dropdown-toggle',
                    'data-bs-toggle' => 'dropdown',
                    'aria-expanded' => 'false',
                ],
                'items' => function () {
                    $items = [];
                    $items[] = (new UrlEntity([
                        'name' => 'Не указано',
                        'url' => [
                            $this->baseUrl,
                            $this->modelName . '[status]' => ArrayHelper::getValue($this->getData(), 'status'),
                            $this->modelName . '[pageLimit]' => ArrayHelper::getValue($this->getData(), 'pageLimit', 20),
                            $this->modelName . '[standardShow]' => (int)ArrayHelper::getValue($this->getData(), 'standardShow'),
                            $this->modelName . '[deviceGroupId]' => ArrayHelper::getValue($this->getData(), 'deviceGroupId'),
                        ],
                        'options' => [
                            'data-pjax' => 0,
                            'class' => 'dropdown-item',
                        ]
                    ]))->make();
                    foreach (ArrayHelper::getValue($this->getData(), 'limitData') as $limit) {
                        $items[] = (new UrlEntity([
                            'name' => $limit,
                            'url' => [
                                $this->baseUrl,
                                $this->modelName . '[status]' => ArrayHelper::getValue($this->getData(), 'status'),
                                $this->modelName . '[pageLimit]' => $limit,
                                'per-page' => $limit,
                                $this->modelName . '[standardShow]' => (int)ArrayHelper::getValue($this->getData(), 'standardShow'),
                                $this->modelName . '[deviceGroupId]' => ArrayHelper::getValue($this->getData(), 'deviceGroupId'),
                            ],
                            'options' => [
                                'data-pjax' => 0,
                                'class' => 'dropdown-item',
                                'style' => (int)ArrayHelper::getValue($this->getData(), 'pageLimit') === $limit ? 'font-weight: bold;' : '',
                            ]
                        ]))->make();
                    }
                    return $items;
                }
            ]),
            new DropdownEntity([
                'name' => 'Статус' . $state,
                'options' => [
                    'class' => 'btn btn-light dropdown-toggle',
                    'data-bs-toggle' => 'dropdown',
                    'aria-expanded' => 'false',
                ],
                'items' => function () {
                    $items = [];
                    $items[] = (new UrlEntity([
                        'name' => 'Не указано',
                        'url' => [
                            $this->baseUrl,
                            $this->modelName . '[status]' => null,
                            $this->modelName . '[pageLimit]' => (int)ArrayHelper::getValue($this->getData(), 'pageLimit', 20),
                            $this->modelName . '[standardShow]' => (int)ArrayHelper::getValue($this->getData(), 'standardShow'),
                            $this->modelName . '[deviceGroupId]' => ArrayHelper::getValue($this->getData(), 'deviceGroupId'),
                        ],
                        'options' => [
                            'data-pjax' => 0,
                            'class' => 'dropdown-item',
                        ]
                    ]))->make();
                    foreach (ArrayHelper::getValue($this->getData(), 'statusData') as $key => $status) {
                        $items[] = (new UrlEntity([
                            'name' => $status,
                            'url' => [
                                $this->baseUrl,
                                $this->modelName . '[status]' => $key,
                                $this->modelName . '[pageLimit]' => (int)ArrayHelper::getValue($this->getData(), 'pageLimit', 20),
                                $this->modelName . '[standardShow]' => (int)ArrayHelper::getValue($this->getData(), 'standardShow'),
                                $this->modelName . '[deviceGroupId]' => ArrayHelper::getValue($this->getData(), 'deviceGroupId'),
                            ],
                            'options' => [
                                'data-pjax' => 0,
                                'class' => 'dropdown-item',
                                'style' => (int)ArrayHelper::getValue($this->getData(), 'status') === $key ? 'font-weight: bold;' : '',
                            ]
                        ]))->make();
                    }
                    return $items;
                }
            ]),
            new DropdownEntity([
                'name' => 'Группа' . $group,
                'options' => [
                    'class' => 'btn btn-light dropdown-toggle',
                    'data-bs-toggle' => 'dropdown',
                    'aria-expanded' => 'false',
                ],
                'items' => function () {
                    $items = [];
                    $items[] = (new UrlEntity([
                        'name' => 'Не указано',
                        'url' => [
                            $this->baseUrl,
                            $this->modelName . '[status]' => ArrayHelper::getValue($this->getData(), 'status'),
                            $this->modelName . '[pageLimit]' => (int)ArrayHelper::getValue($this->getData(), 'pageLimit', 20),
                            $this->modelName . '[standardShow]' => (int)ArrayHelper::getValue($this->getData(), 'standardShow'),
                            $this->modelName . '[deviceGroupId]' => null,
                        ],
                        'options' => [
                            'data-pjax' => 0,
                            'class' => 'dropdown-item',
                        ]
                    ]))->make();
                    foreach (ArrayHelper::getValue($this->getData(), 'deviceGroupData') as $key => $groupName) {
                        $items[] = (new UrlEntity([
                            'name' => $groupName,
                            'url' => [
                                $this->baseUrl,
                                $this->modelName . '[status]' => ArrayHelper::getValue($this->getData(), 'status'),
                                $this->modelName . '[pageLimit]' => (int)ArrayHelper::getValue($this->getData(), 'pageLimit', 20),
                                $this->modelName . '[standardShow]' => (int)ArrayHelper::getValue($this->getData(), 'standardShow'),
                                $this->modelName . '[deviceGroupId]' => $key,
                            ],
                            'options' => [
                                'data-pjax' => 0,
                                'class' => 'dropdown-item',
                                'style' => ArrayHelper::getValue($this->getData(), 'deviceGroupId') === $key ? 'font-weight: bold;' : '',
                            ]
                        ]))->make();
                    }
                    return $items;
                }
            ])
        ];
        $this->setItems($items);
    }
}
