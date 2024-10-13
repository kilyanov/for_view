<?php

declare(strict_types=1);

namespace app\modules\industry\models\search;

use app\modules\industry\models\OrderList;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class OrderListSearch extends OrderList
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
            [['type', 'contractId', 'number', 'year', 'status', 'createdAt', 'updatedAt', 'numberScore', 'description'], 'safe'],
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
        $query = OrderList::find()->hidden()
            ->with(['contractRelation']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'sort' => SORT_ASC,
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

        $query->andFilterWhere(['like', OrderList::tableName() . '.[[number]]', $this->number])
            ->andFilterWhere([OrderList::tableName() . '.[[year]]' => $this->year])
            ->andFilterWhere([OrderList::tableName() . '.[[type]]' => $this->type])
            ->andFilterWhere([OrderList::tableName() . '.[[contractId]]' => $this->contractId])
            ->andFilterWhere([OrderList::tableName() . '.[[status]]' => $this->status])
            ->andFilterWhere([OrderList::tableName() . '.[[numberScore]]' => $this->numberScore]);

        return $dataProvider;
    }
}
