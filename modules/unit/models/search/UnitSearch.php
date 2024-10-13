<?php

declare(strict_types=1);

namespace app\modules\unit\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\unit\models\Unit;

class UnitSearch extends Unit
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
            [['id', 'parentId'], 'string'],
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
        $query = Unit::find()->with(['parentRelation'])->hidden();

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

        $query->andFilterWhere([Unit::tableName() . '.[[parentId]]' => $this->parentId])
            ->andFilterWhere([Unit::tableName() . '.[[hidden]]' => $this->hidden])
            ->andFilterWhere(['like', Unit::tableName() . '.[[name]]', $this->name])
            ->andFilterWhere(['like', Unit::tableName() . '.[[description]]', $this->description]);

        return $dataProvider;
    }
}
