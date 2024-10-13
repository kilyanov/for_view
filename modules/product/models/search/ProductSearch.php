<?php

declare(strict_types=1);

namespace app\modules\product\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\product\models\Product;

class ProductSearch extends Product
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
            [['hidden', 'sort'], 'integer'],
            [['id',], 'string'],
            [['name', 'mark', 'description', 'createdAt', 'updatedAt'], 'safe'],
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
        $query = Product::find()->hidden();

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

        $query->andFilterWhere([Product::tableName() . '.[[hidden]]' => $this->hidden])
            ->andFilterWhere(['like', Product::tableName() . '.[[name]]', $this->name])
            ->andFilterWhere(['like', Product::tableName() . '.[[mark]]', $this->mark])
            ->andFilterWhere(['like', Product::tableName() . '.[[description]]', $this->description]);

        return $dataProvider;
    }
}
