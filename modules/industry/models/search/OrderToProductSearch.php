<?php

declare(strict_types=1);

namespace app\modules\industry\models\search;

use app\modules\industry\models\OrderToProduct;
use app\modules\industry\models\RepairProduct;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class OrderToProductSearch extends OrderToProduct
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
            [['orderId', 'productId', 'comment', 'hidden'], 'safe'],
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
        $query = OrderToProduct::find()
            ->with(['orderRelation'])
            ->joinWith(['productRelation'])
            ->andWhere([OrderToProduct::tableName() . '.[[orderId]]' => $this->orderId]);

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

        $query->andFilterWhere(['like', OrderToProduct::tableName() . '.[[comment]]', $this->comment])
            ->andFilterWhere(['like', RepairProduct::tableName() . '.[[number]]', $this->productId])
            ->andFilterWhere([OrderToProduct::tableName() . '.[[hidden]]' => $this->hidden]);

        return $dataProvider;
    }
}
