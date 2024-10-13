<?php

declare(strict_types=1);

namespace app\modules\industry\models\search;

use app\modules\industry\models\RepairProduct;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class RepairProductSearch extends RepairProduct
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
            [['productId', 'number', 'comment', 'createdAt', 'updatedAt', 'productNodeId', 'productBlockId',], 'safe'],
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
        $query = RepairProduct::find()->hidden();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
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

        $query->andFilterWhere(['like', RepairProduct::tableName() . '.[[number]]', $this->number])
            ->andFilterWhere([RepairProduct::tableName() . '.[[productId]]' => $this->productId])
            ->andFilterWhere([RepairProduct::tableName() . '.[[productNodeId]]' => $this->productNodeId])
            ->andFilterWhere([RepairProduct::tableName() . '.[[productBlockId]]' => $this->productBlockId]);

        return $dataProvider;
    }
}
