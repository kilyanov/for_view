<?php

declare(strict_types=1);

namespace app\modules\industry\models\search;

use app\modules\industry\models\Machine;
use app\modules\industry\models\query\MachineQuery;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class MachineSearch extends Machine
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
            [['id', 'productId'], 'string'],
            [['name', 'productId', 'number', 'comment', 'createdAt', 'updatedAt'], 'safe'],
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
        /** @var MachineQuery $query */
        $query = Machine::find()
            ->hidden()
            ->with(['productRelation']);

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

        $query->andFilterWhere([Machine::tableName() . '.[[productId]]' => $this->productId])
            ->andFilterWhere(['like', Machine::tableName() . '.[[name]]', $this->name])
            ->andFilterWhere([Machine::tableName() . '.[[number]]' => $this->number])
            ->andFilterWhere(['like', Machine::tableName() . '.[[comment]]', $this->comment]);

        return $dataProvider;
    }
}
