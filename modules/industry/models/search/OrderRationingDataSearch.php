<?php

declare(strict_types=1);

namespace app\modules\industry\models\search;

use app\modules\industry\models\OrderRationingData;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class OrderRationingDataSearch extends OrderRationingData
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
                    'productPartId',
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
        $query = OrderRationingData::find()->hidden()
            ->with(['specialRelation', 'unitRelation', 'machineRelation'])
            ->andWhere(['rationingId' => $this->rationingId]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'sort' => SORT_ASC,
                ]
            ]
        ]);

        $dataProvider->pagination = false;

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', OrderRationingData::tableName() . '.[[name]]', $this->name])
            ->andFilterWhere(['like', OrderRationingData::tableName() . '.[[comment]]', $this->comment])
            ->andFilterWhere(['like', OrderRationingData::tableName() . '.[[norma]]', $this->norma])
            ->andFilterWhere([OrderRationingData::tableName() . '.[[machineId]]' => $this->machineId])
            ->andFilterWhere([OrderRationingData::tableName() . '.[[unitId]]' => $this->unitId])
            ->andFilterWhere([OrderRationingData::tableName() . '.[[specialId]]' => $this->specialId])
            ->andFilterWhere([OrderRationingData::tableName() . '.[[point]]' => $this->point]);

        return $dataProvider;
    }
}
