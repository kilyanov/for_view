<?php

declare(strict_types=1);

namespace app\modules\industry\modules\presentation\widgets;

use app\modules\personal\models\Personal;
use app\modules\personal\modules\group\models\PersonalGroup;
use Exception;
use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;

/**
 *
 * @property-read array[] $listItems
 */
class PresentBookButtonListWidget extends Widget
{
    /**
     * @var string|null
     */
    public ?string $active = null;

    /**
     * @var int|null
     */
    public ?int $year = null;

    /**
     * @var int|null
     */
    public ?int $month = null;

    /**
     * @var string
     */
    public string $unitId;

    /**
     * @return string
     * @throws Exception
     */
    public function run(): string
    {
        $this->active = ArrayHelper::getValue(Yii::$app->request->get(), 'groupId');
        $this->year = (int)ArrayHelper::getValue(Yii::$app->request->get('PresentationBookSearch'), 'year', date('Y'));
        $this->month = (int)ArrayHelper::getValue(Yii::$app->request->get('PresentationBookSearch'), 'month', date('m'));

        return $this->render('list', ['items' => $this->getListItems()]);
    }

    /**
     * @return array[]
     */
    protected function getListItems(): array
    {
        $groups = Personal::find()
            ->joinWith(['groupRelation'])
            ->select([
                'nameGroup' => PersonalGroup::tableName() . '.[[name]]',
                'groupId' => Personal::tableName() . '.[[groupId]]'
            ])
            ->andWhere([Personal::tableName() . '.[[unitId]]' => $this->unitId])
            ->orderBy(PersonalGroup::tableName() . '.[[sort]]')
            ->groupBy(['nameGroup', 'groupId'])
            ->asArray()
            ->all();
        $items = [];
        foreach ($groups as $group) {
            if ($group['nameGroup'] !== null) {
                $items[] = [
                    'text' => $group['nameGroup'],
                    'url' => [
                        '/industry/presentation/default/index',
                        'groupId' => $group['groupId'],
                        'PresentationBookSearch[year]' => $this->year,
                        'PresentationBookSearch[month]' => $this->month,
                    ],
                    'options' => [
                        'data-pjax' => 0,
                        'class' => $this->active === $group['groupId'] ? 'btn btn-danger' : 'btn btn-light',
                        'style' => 'margin-right:2px;'
                    ]
                ];
            }
        }
        $itemsDefault = [
            [
                'text' => 'Сводная ведомость',
                'url' => [
                    '/industry/presentation/default/index',
                    'PresentationBookSearch[year]' => $this->year,
                    'PresentationBookSearch[month]' => $this->month,
                ],
                'options' => [
                    'data-pjax' => 0,
                    'class' => $this->active === null ? 'btn btn-danger' : 'btn btn-light',
                    'style' => 'margin-right:2px;'
                ]
            ],
            [
                'text' => 'График',
                'url' => [
                    '/industry/presentation/default/chart',
                    'PresentationBookSearch[year]' => $this->year,
                    'PresentationBookSearch[month]' => $this->month,
                ],
                'options' => [
                    'data-pjax' => 0,
                    'class' => 'btn btn-light',
                ]
            ],
        ];

        return ArrayHelper::merge($items, $itemsDefault);
    }
}
