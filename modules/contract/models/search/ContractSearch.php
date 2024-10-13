<?php

declare(strict_types=1);

namespace app\modules\contract\models\search;

use app\modules\contract\models\Contract;
use app\modules\institution\models\Institution;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class ContractSearch extends Contract
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['hidden', 'status'], 'integer'],
            ['id', 'string'],
            [
                [
                    'number', 'description', 'createdAt',
                    'updatedAt', 'name', 'description',
                    'dateFinding', 'validityPeriod', 'status', 'organizationId'
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
        $query = Contract::find()->hidden();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'validityPeriod' => SORT_DESC,
                ]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', Contract::tableName() . '.[[name]]', $this->name])
            ->andFilterWhere(['like', Contract::tableName() . '.[[description]]', $this->description])
            ->andFilterWhere(['like', Contract::tableName() . '.[[number]]', $this->number])
            ->andFilterWhere([Contract::tableName() . '.[[status]]' => $this->status]);

        if (!empty($this->entityId)) {
            $query->joinWith(['institutionRelation']);
            $query->andFilterWhere(['like', Institution::tableName() . '.[[name]]', $this->institutionId]);
        }

        return $dataProvider;
    }
}
