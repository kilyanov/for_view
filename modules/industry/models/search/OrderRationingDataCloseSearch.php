<?php

declare(strict_types=1);

namespace app\modules\industry\models\search;

use app\modules\industry\models\OrderRationingDataClose;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class OrderRationingDataCloseSearch extends OrderRationingDataClose
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
            ['id', 'string'],
            [
                ['orderRationingDataId', 'norma', 'year', 'month'],
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
        $query = OrderRationingDataClose::find()
            ->with([
                'orderRationingDataRelation',
            ])
            ->andFilterWhere([
                OrderRationingDataClose::tableName() . '.[[orderRationingDataId]]' => $this->orderRationingDataId
            ]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);

        if ($this->pageLimit === null) {
            $dataProvider->pagination = false;
        }
        else {
            $dataProvider->pagination = [
                'pageSize' => $this->pageLimit,
            ];
        }

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([OrderRationingDataClose::tableName() . '.[[year]]' => $this->year])
            ->andFilterWhere([OrderRationingDataClose::tableName() . '.[[norma]]' => $this->norma])
            ->andFilterWhere([OrderRationingDataClose::tableName() . '.[[month]]' => $this->month]);

        return $dataProvider;
    }
}
