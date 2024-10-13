<?php

declare(strict_types=1);

namespace app\modules\contract\models\search;

use app\modules\contract\models\ContractSpecification;
use app\modules\product\models\Product;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class ContractSpecificationSearch extends ContractSpecification
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['hidden',], 'integer'],
            ['id', 'string'],
            [
                ['contractId', 'productId', 'productNodeId', 'productBlockId', 'factoryNumber', 'comment'],
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
        $query = ContractSpecification::find()
            ->hidden()
            ->contract($this->contractId);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'productId' => SORT_ASC,
                ]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'like',
            ContractSpecification::tableName() . '.[[factoryNumber]]',
            $this->factoryNumber
        ]);
        if (!empty($this->productId)) {
            $query->joinWith(['productRelation']);
            $query->andWhere(['like', Product::tableName() . '.[[mark]]', $this->productId]);
        }
        if (!empty($this->productPartId)) {
            $query->joinWith(['productRelation']);
            $query->andWhere(['like', Product::tableName() . '.[[name]]', $this->productPartId]);
        }

        return $dataProvider;
    }
}
