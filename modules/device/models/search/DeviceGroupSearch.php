<?php

declare(strict_types=1);

namespace app\modules\device\models\search;

use app\modules\device\models\DeviceGroup;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class DeviceGroupSearch extends DeviceGroup
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
            ['id', 'string'],
            [['name', 'status', 'description', 'createdAt', 'updatedAt'], 'safe'],
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
        $query = DeviceGroup::find()->hidden();

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

        $query->andFilterWhere(['like', DeviceGroup::tableName() . '.[[name]]', $this->name])
            ->andFilterWhere([DeviceGroup::tableName() . '.[[status]]' => $this->status])
            ->andFilterWhere(['like', DeviceGroup::tableName() . '.[[description]]', $this->description]);

        return $dataProvider;
    }
}
