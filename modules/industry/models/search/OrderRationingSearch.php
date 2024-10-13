<?php

declare(strict_types=1);

namespace app\modules\industry\models\search;

use app\modules\industry\models\OrderRationing;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class OrderRationingSearch extends OrderRationing
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            ['hidden', 'integer'],
            ['id', 'string'],
            [
                ['productId', 'name', 'norma', 'unitId', 'impactId', 'comment', 'orderId'],
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
        $query = OrderRationing::find()->hidden()
            ->joinWith([
                'impactRelation',
                'orderRelation',
                'productRelation',
                'productBlockRelation',
                'productNodeRelation',
                'unitRelation'
            ])
            ->andWhere([OrderRationing::tableName() . '.[[orderId]]' => $this->orderId]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', OrderRationing::tableName() . '.[[name]]', $this->name])
            ->andFilterWhere(['like', OrderRationing::tableName() . '.[[comment]]', $this->comment])
            ->andFilterWhere([OrderRationing::tableName() . '.[[productId]]' => $this->productId])
            ->andFilterWhere([OrderRationing::tableName() . '.[[norma]]' => $this->norma])
            ->andFilterWhere([OrderRationing::tableName() . '.[[unitId]]' => $this->unitId])
            ->andFilterWhere([OrderRationing::tableName() . '.[[impactId]]' => $this->impactId]);

        return $dataProvider;
    }
}
