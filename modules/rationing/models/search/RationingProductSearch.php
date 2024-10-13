<?php

declare(strict_types=1);

namespace app\modules\rationing\models\search;

use app\modules\rationing\models\RationingProduct;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class RationingProductSearch extends RationingProduct
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
                    'productId', 'name',
                    'norma', 'unitId',
                    'impactId', 'comment',
                    'productNodeId', 'productBlockId',
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
        $query = RationingProduct::find()->hidden()
            ->with(['rationingProductDatasRelation']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', RationingProduct::tableName() . '.[[name]]', $this->name])
            ->andFilterWhere(['like', RationingProduct::tableName() . '.[[comment]]', $this->comment])
            ->andFilterWhere([RationingProduct::tableName() . '.[[productId]]' => $this->productId])
            ->andFilterWhere([RationingProduct::tableName() . '.[[productNodeId]]' => $this->productNodeId])
            ->andFilterWhere([RationingProduct::tableName() . '.[[productBlockId]]' => $this->productBlockId])
            ->andFilterWhere([RationingProduct::tableName() . '.[[norma]]' => $this->norma])
            ->andFilterWhere([RationingProduct::tableName() . '.[[unitId]]' => $this->unitId])
            ->andFilterWhere([RationingProduct::tableName() . '.[[impactId]]' => $this->impactId])
            ->andFilterWhere([RationingProduct::tableName() . '.[[hidden]]' => $this->hidden]);

        return $dataProvider;
    }
}
