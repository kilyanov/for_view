<?php

declare(strict_types=1);

namespace app\modules\impact\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\impact\models\Impact;

class ImpactSearch extends Impact
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
        $query = Impact::find()->hidden();

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

        $query->andFilterWhere([Impact::tableName() . '.[[hidden]]' => $this->hidden])
            ->andFilterWhere(['like', Impact::tableName() . '.[[name]]', $this->name])
            ->andFilterWhere(['like', Impact::tableName() . '.[[mark]]', $this->mark])
            ->andFilterWhere(['like', Impact::tableName() . '.[[description]]', $this->description]);

        return $dataProvider;
    }
}
