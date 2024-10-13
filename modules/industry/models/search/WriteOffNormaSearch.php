<?php

declare(strict_types=1);

namespace app\modules\industry\models\search;

use app\modules\industry\models\OrderList;
use app\modules\industry\models\OrderRationingDataClose;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

class WriteOffNormaSearch extends OrderRationingDataCloseSearch
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            ['id', 'string'],
            [
                ['orderRationingDataId', 'norma', 'year', 'month', 'order', 'sumHour', 'number_order'],
                'safe'
            ],
        ];
    }

    /**
     * @param ActiveQuery $query
     * @param array $params
     * @return ActiveDataProvider
     */
    protected function getDataProvider(ActiveQuery $query, array $params): ActiveDataProvider
    {
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'year' => SORT_DESC,
                    'month' => SORT_DESC,
                ]
            ]
        ]);

        if ($this->pageLimit === null) {
            $dataProvider->pagination = false;
        } else {
            $dataProvider->pagination = [
                'pageSize' => $this->pageLimit,
            ];
        }

        return $dataProvider;
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = OrderRationingDataClose::find()
            ->joinWith(['orderRationingDataRelation.rationingRelation.orderRelation'])
            ->with([
                'orderRationingDataRelation',
            ])
            ->andFilterWhere([
                OrderRationingDataClose::tableName() . '.[[orderRationingDataId]]' => $this->orderRationingDataId
            ]);
        $this->load($params);

        if (!$this->validate()) {
            return $this->getDataProvider($query, $params);
        }

        $query->andFilterWhere([OrderRationingDataClose::tableName() . '.[[year]]' => $this->year])
            ->andFilterWhere([OrderRationingDataClose::tableName() . '.[[norma]]' => $this->norma])
            ->andFilterWhere([OrderRationingDataClose::tableName() . '.[[month]]' => $this->month])
            ->andFilterWhere(['like', OrderList::tableName() . '.[[number]]', $this->number_order]);

        return $this->getDataProvider($query, $params);
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function searchMonth(array $params): ActiveDataProvider
    {
        $query = OrderRationingDataClose::find()
            ->joinWith(['orderRationingDataRelation.rationingRelation.orderRelation'])
            ->select([
                OrderList::tableName() . '.[[number]]',
                OrderList::tableName() . '.[[number]] as number_order',
                'count(' . OrderList::tableName() . '.[[number]]) as order_id',
                OrderRationingDataClose::tableName() . '.[[year]]',
                OrderRationingDataClose::tableName() . '.[[month]]',
                'SUM(' . OrderRationingDataClose::tableName() . '.[[norma]]) as sumHour'])
            ->groupBy([
                OrderList::tableName() . '.[[number]]',
                OrderRationingDataClose::tableName() . '.[[year]]',
                OrderRationingDataClose::tableName() . '.[[month]]']);

        $this->load($params);

        if (!$this->validate()) {
            return $this->getDataProvider($query, $params);
        }

        $query->andFilterWhere([OrderRationingDataClose::tableName() . '.[[year]]' => $this->year])
            ->andFilterWhere([OrderRationingDataClose::tableName() . '.[[norma]]' => $this->norma])
            ->andFilterWhere([OrderRationingDataClose::tableName() . '.[[month]]' => $this->month])
            ->andFilterWhere(['like', OrderList::tableName() . '.[[number]]', $this->number_order]);

         return $this->getDataProvider($query, $params);
    }
}
