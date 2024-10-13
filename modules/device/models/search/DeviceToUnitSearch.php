<?php

declare(strict_types=1);

namespace app\modules\device\models\search;

use app\modules\device\models\DeviceToUnit;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class DeviceToUnitSearch extends DeviceToUnit
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            ['hidden', 'integer'],
            ['id', 'string'],
            [['deviceId', 'unitId', 'description', 'hidden', 'createdAt', 'updatedAt'], 'safe'],
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
        $query = DeviceToUnit::find()
            ->with(['unitRelation'])
            ->hidden()
            ->andWhere([DeviceToUnit::tableName() . '.[[deviceId]]' => $this->deviceId]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'status' => SORT_ASC,
                ]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', DeviceToUnit::tableName() . '.[[description]]', $this->description])
        ->andFilterWhere([DeviceToUnit::tableName() . '.[[status]]' => $this->status])
        ->andFilterWhere([DeviceToUnit::tableName() . '.[[hidden]]' => $this->hidden]);

        return $dataProvider;
    }
}
