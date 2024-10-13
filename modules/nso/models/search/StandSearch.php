<?php

declare(strict_types=1);

namespace app\modules\nso\models\search;

use app\modules\nso\models\Stand;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

class StandSearch extends Stand
{
    /**
     * @var int|bool|null
     */
    public int|bool|null $pageLimit = null;

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
                    'unitId', 'number', 'name', 'mark',
                    'inventoryNumber', 'standardHours',
                    'category', 'conservation', 'dateVerifications',
                    'description'
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
        $query = Stand::find()->hidden();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'dateVerifications' => SORT_DESC,
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

        $query->andFilterWhere(['like', Stand::tableName() . '.[[name]]', $this->name])
            ->andFilterWhere(['like', Stand::tableName() . '.[[number]]', $this->number])
            ->andFilterWhere(['like', Stand::tableName() . '.[[mark]]', $this->mark])
            ->andFilterWhere(['like', Stand::tableName() . '.[[inventoryNumber]]', $this->inventoryNumber])
            ->andFilterWhere(['like', Stand::tableName() . '.[[standardHours]]', $this->standardHours])
            ->andFilterWhere(['like', Stand::tableName() . '.[[category]]', $this->category])
            ->andFilterWhere(['like', Stand::tableName() . '.[[conservation]]', $this->conservation])
            ->andFilterWhere(['like', Stand::tableName() . '.[[description]]', $this->description])
            ->andFilterWhere([Stand::tableName() . '.[[unitId]]' => $this->unitId]);

        if ($this->dateVerifications) {
            $query->andWhere(
                new Expression(
                    'DATE_FORMAT([[dateVerifications]], "%Y-%m-%d") = :date'
                ),
                [':date' => $this->dateVerifications]
            );
        }

        return $dataProvider;
    }
}
