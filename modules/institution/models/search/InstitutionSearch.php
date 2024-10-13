<?php

declare(strict_types=1);

namespace app\modules\institution\models\search;

use app\modules\institution\models\Institution;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class InstitutionSearch extends Institution
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
            [['hidden'], 'integer'],
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
        $query = Institution::find()->hidden();

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

        $query->andFilterWhere([Institution::tableName() . '.[[hidden]]' => $this->hidden])
            ->andFilterWhere(['like', Institution::tableName() . '.[[name]]', $this->name])
            ->andFilterWhere(['like', Institution::tableName() . '.[[address]]', $this->address])
            ->andFilterWhere(['like', Institution::tableName() . '.[[description]]', $this->description])
            ->andFilterWhere(['like', Institution::tableName() . '.[[requisites]]', $this->requisites]);

        return $dataProvider;
    }
}
