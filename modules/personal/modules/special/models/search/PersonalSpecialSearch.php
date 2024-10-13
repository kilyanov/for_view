<?php

declare(strict_types=1);

namespace app\modules\personal\modules\special\models\search;

use app\modules\personal\modules\special\models\PersonalSpecial;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class PersonalSpecialSearch extends PersonalSpecial
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
            [['id'], 'string'],
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
        $query = PersonalSpecial::find()->hidden();

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

        $query->andFilterWhere(['like', PersonalSpecial::tableName() . '.[[name]]', $this->name])
            ->andFilterWhere([PersonalSpecial::tableName() . '.[[hidden]]' => $this->hidden])
            ->andFilterWhere(['like', PersonalSpecial::tableName() . '.[[description]]', $this->description]);

        return $dataProvider;
    }
}
