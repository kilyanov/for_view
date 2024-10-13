<?php

declare(strict_types=1);

namespace app\widgets;

use app\common\rbac\CollectionRolls;
use Yii;
use yii\base\Widget;
use yii\bootstrap5\Html;

/**
 *
 * @property-read array $items
 */
class MenuHeaderWidget extends Widget
{
    /**
     * @return string
     */
    public function run(): string
    {
        return $this->render('menu-header', ['items' => $this->getItems()]);
    }

    public function getItems(): array
    {
        return [
            [
                'label' => 'Справочники',
                'items' => [
                    [
                        'label' => 'Подразделения',
                        'url' => ['/unit']
                    ],
                    [
                        'label' => 'Специальности',
                        'url' => ['/personal/special']
                    ],
                    [
                        'label' => 'Группы',
                        'url' => ['/personal/group']
                    ],
                    [
                        'label' => 'Персонал',
                        'url' => ['/personal']
                    ],
                    [
                        'label' => 'Виды воздействия',
                        'url' => ['/impact']
                    ],
                    [
                        'label' => 'Изделия',
                        'url' => ['/product']
                    ],
                    [
                        'label' => 'Организации',
                        'url' => ['/institution']
                    ],
                    [
                        'label' => 'Контракты',
                        'url' => ['/contract']
                    ],
                    [
                        'label' => 'Ресурсы',
                        'url' => ['/resource']
                    ],
                ],
                'visible' => Yii::$app->user->can(CollectionRolls::ROLE_ROOT)
            ],
            [
                'label' => 'Поверка',
                'items' => [
                    [
                        'label' => 'Группы',
                        'url' => ['/device/group']
                    ],
                    [
                        'label' => 'Типы',
                        'url' => ['/device/type']
                    ],
                    [
                        'label' => 'Наименование',
                        'url' => ['/device/name']
                    ],
                    [
                        'label' => 'Тех. характеристики',
                        'url' => ['/device/property']
                    ],
                    [
                        'label' => 'Список',
                        'url' => ['/device/default']
                    ],
                ],
                'visible' => Yii::$app->user->can(CollectionRolls::ROLE_VERIFIER)
            ],
            [
                'label' => 'Производство',
                'items' => [
                    [
                        'label' => 'Машинокомплекты',
                        'url' => ['/industry/machine'],
                        'visible' => Yii::$app->user->can(CollectionRolls::ROLE_ROOT)
                    ],
                    [
                        'label' => 'Ремонтируемые изделия',
                        'url' => ['/industry/product'],
                        'visible' => Yii::$app->user->can(CollectionRolls::ROLE_ROOT)
                    ],
                    [
                        'label' => 'Заказы',
                        'url' => ['/industry/order']
                    ],
                    [
                        'label' => 'Списание Н/Ч',
                        'url' => ['/industry/order/write-off-norma']
                    ],
                    [
                        'label' => 'Заявки',
                        'url' => ['/application']
                    ],
                ],
                'visible' => Yii::$app->user->can(CollectionRolls::ROLE_SECONDARY_CIL)
            ],

            [
                'label' => 'НСО',
                'items' => [
                    [
                        'label' => 'Список',
                        'url' => ['/nso']
                    ],
                    [
                        'label' => 'Графки НСО',
                        'url' => ['/nso/chart']
                    ],
                ],
                'visible' => Yii::$app->user->can(CollectionRolls::ROLE_SECONDARY_CIL)
            ],
            [
                'label' => 'Книги предъявлений',
                'url' => ['/industry/presentation'],
                'visible' => Yii::$app->user->can(CollectionRolls::ROLE_ENGINEER_CIL)
            ],
            [
                'label' => 'Нормировки',
                'items' => [
                    [
                        'label' => 'Ремонт СИ',
                        'url' => ['/rationing/device']
                    ],
                    [
                        'label' => 'Ремонт ВВТ',
                        'url' => ['/rationing/product']
                    ],
                ],
                'visible' => Yii::$app->user->can(CollectionRolls::ROLE_SECONDARY_CIL)
            ],
            Yii::$app->user->isGuest
                ? ['label' => 'Войти', 'url' => ['/login']]
                : '<li class="nav-item">'
                . Html::beginForm(['/logout'])
                . Html::submitButton(
                    'Выйти (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'nav-link btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
        ];
    }
}
