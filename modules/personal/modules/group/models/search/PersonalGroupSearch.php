<?php

declare(strict_types=1);

namespace app\modules\personal\modules\group\models\search;

use app\modules\personal\modules\group\models\PersonalGroup;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class PersonalGroupSearch extends PersonalGroup
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
            [['name', 'description', 'createdAt', 'updatedAt'], 'safe'],
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
        $query = PersonalGroup::find()->hidden();

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

        $query->andFilterWhere(['like', PersonalGroup::tableName() . '.[[name]]', $this->name])
            ->andFilterWhere([PersonalGroup::tableName() . '.[[hidden]]' => $this->hidden])
            ->andFilterWhere(['like', PersonalGroup::tableName() . '.[[description]]', $this->description]);

        return $dataProvider;
    }
}
