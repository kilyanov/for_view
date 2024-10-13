<?php

declare(strict_types=1);

namespace app\modules\rationing\models\search;

use app\modules\industry\models\Machine;
use app\modules\rationing\models\RationingProductData;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class RationingProductDataSearch extends RationingProductData
{
    /**
     * @var int|bool|null
     */
    public int|bool|null $pageLimit = 1000;

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
                    'type',
                    'rationingId',
                    'point',
                    'subItem',
                    'name',
                    'machineId',
                    'unitId',
                    'ed',
                    'countItems',
                    'periodicity',
                    'category',
                    'norma',
                    'normaAll',
                    'specialId',
                    'comment',
                    'checkList',
                    'limit'
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
        $query = RationingProductData::find()->hidden()
            ->andFilterWhere(['rationingId' => $this->rationingId])
        ->joinWith(['machineRelation', 'unitRelation', ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'sort' => SORT_ASC,
                    'machineId' => [
                        'asc' => [Machine::tableName() . '.[[sort]]' => SORT_ASC],
                        'desc' => [Machine::tableName() . '.[[sort]]' => SORT_DESC],
                    ]
                ]
            ],
        ]);


        $dataProvider->pagination = false;

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', RationingProductData::tableName() . '.[[name]]', $this->name])
            ->andFilterWhere(['like', RationingProductData::tableName() . '.[[comment]]', $this->comment])
            ->andFilterWhere(['like', RationingProductData::tableName() . '.[[norma]]', $this->norma])
            ->andFilterWhere([RationingProductData::tableName() . '.[[machineId]]' => $this->machineId])
            ->andFilterWhere([RationingProductData::tableName() . '.[[unitId]]' => $this->unitId])
            ->andFilterWhere([RationingProductData::tableName() . '.[[specialId]]' => $this->specialId])
            ->andFilterWhere([RationingProductData::tableName() . '.[[point]]' => $this->point])
            ->andFilterWhere([RationingProductData::tableName() . '.[[hidden]]' => $this->hidden]);

        return $dataProvider;
    }
}
