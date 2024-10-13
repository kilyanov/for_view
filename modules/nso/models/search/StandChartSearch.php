<?php

declare(strict_types=1);

namespace app\modules\nso\models\search;

use app\modules\nso\models\Stand;
use app\modules\nso\models\StandChart;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

/**
 *
 * @property string $unitId Подразделение
 * @property string|null $number Номер стенда
 * @property string|null $inventoryNumber Инвентарный номер
 *
 */
class StandChartSearch extends StandChart
{
    /**
     * @var int|bool|null
     */
    public int|bool|null $pageLimit = null;

    /**
     * @var string|array|null
     */
    public string|array|null $unitId = null;

    /**
     * @var string|null
     */
    public ?string $inventoryNumber = null;

    /**
     * @var string|null
     */
    public ?string $number = null;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            ['hidden', 'integer'],
            ['id', 'string'],
            [
                [
                    'standId',
                    'year',
                    'monthPlan',
                    'monthFact',
                    'dateFact',
                    /** виртуальные поля */
                    'unitId',
                    'number',
                    'inventoryNumber',
                ],
                'safe'
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios(): array
    {
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = StandChart::find()->hidden()
            ->joinWith(['standRelation']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'monthPlan' => SORT_ASC,
                ]
            ]
        ]);

        if ($this->pageLimit === false) {
            $dataProvider->pagination = false;
        }

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        if (!empty($this->standId)) {
            $query->andWhere(['or',
                ['like', Stand::tableName() . '.[[name]]', $this->standId],
                ['like', Stand::tableName() . '.[[mark]]', $this->standId],
                ['like', Stand::tableName() . '.[[description]]', $this->standId],
            ]);
        }

        $query->andFilterWhere(['like', Stand::tableName() . '.[[number]]', $this->number])
            ->andFilterWhere(['like', Stand::tableName() . '.[[inventoryNumber]]', $this->inventoryNumber])
            ->andFilterWhere([Stand::tableName() . '.[[unitId]]' => $this->unitId])
            ->andFilterWhere([StandChart::tableName() . '.[[year]]' => $this->year])
            ->andFilterWhere([StandChart::tableName() . '.[[monthPlan]]' => $this->monthPlan])
            ->andFilterWhere([StandChart::tableName() . '.[[monthFact]]' => $this->monthFact]);

        if ($this->dateFact) {
            $query->andWhere(
                new Expression(
                    'DATE_FORMAT([[dateVerifications]], "%Y-%m-%d") = :date'
                ),
                [':date' => $this->dateFact]
            );
        }

        if (empty($this->year)) {
            $query->andWhere(['year' => date('Y')]);
        }

        return $dataProvider;
    }
}
