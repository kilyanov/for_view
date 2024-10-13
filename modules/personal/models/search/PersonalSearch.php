<?php

declare(strict_types=1);

namespace app\modules\personal\models\search;

use app\modules\personal\models\Personal;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class PersonalSearch extends Personal
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
            [['hidden', 'status'], 'integer'],
            ['id', 'string'],
            [
                [
                    'specialId', 'unitId', 'groupId', 'fistName',
                    'lastName', 'secondName', 'discharge', 'salary',
                    'ratio', 'createdAt', 'updatedAt', 'pageLimit', 'status', 'description'
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
        $query = Personal::find()->hidden()
            ->with(['groupRelation', 'specialRelation', 'unitRelation']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'typeSalary' => SORT_ASC,
                    'groupId' => SORT_ASC,
                    'fistName' => SORT_ASC,
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

        $query->andFilterWhere([Personal::tableName() . '.[[unitId]]' => $this->unitId])
            ->andFilterWhere([Personal::tableName() . '.[[groupId]]' => $this->groupId])
            ->andFilterWhere([Personal::tableName() . '.[[specialId]]' => $this->specialId])
            ->andFilterWhere([Personal::tableName() . '.[[status]]' => $this->status])
            ->andFilterWhere([Personal::tableName() . '.[[hidden]]' => $this->hidden])
            ->andFilterWhere(['like', Personal::tableName() . '.[[fistName]]', $this->fistName])
            ->andFilterWhere(['like', Personal::tableName() . '.[[lastName]]', $this->lastName])
            ->andFilterWhere(['like', Personal::tableName() . '.[[secondName]]', $this->secondName])
            ->andFilterWhere(['like', Personal::tableName() . '.[[discharge]]', $this->discharge])
            ->andFilterWhere(['like', Personal::tableName() . '.[[salary]]', $this->salary])
            ->andFilterWhere(['like', Personal::tableName() . '.[[ratio]]', $this->ratio]);

        return $dataProvider;
    }
}
