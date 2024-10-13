<?php

declare(strict_types=1);

namespace app\modules\resource\models\search;

use app\modules\resource\models\query\ResourceQuery;
use app\modules\resource\models\Resource;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class ResourceSearch extends Resource
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            ['hidden', 'integer'],
            ['id', 'string'],
            [['name', 'mark', 'description', 'createdAt', 'updatedAt', 'stamp', 'size', 'ed'], 'safe'],
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
        /** @var ResourceQuery $query */
        $query = Resource::find()->hidden();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', Resource::tableName() . '.[[name]]', $this->name])
            ->andFilterWhere(['like', Resource::tableName() . '.[[mark]]', $this->mark])
            ->andFilterWhere(['like', Resource::tableName() . '.[[description]]', $this->description])
            ->andFilterWhere(['like', Resource::tableName() . '.[[stamp]]', $this->stamp])
            ->andFilterWhere(['like', Resource::tableName() . '.[[size]]', $this->size])
            ->andFilterWhere(['like', Resource::tableName() . '.[[ed]]', $this->ed])
            ->andFilterWhere([Resource::tableName() . '.[[hidden]]' => $this->hidden]);

        return $dataProvider;
    }
}
