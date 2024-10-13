<?php

declare(strict_types=1);

namespace app\modules\device\models\search;

use app\modules\device\models\DeviceToImpact;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class DeviceToImpactSearch extends DeviceToImpact
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            ['hidden', 'integer'],
            ['id', 'string'],
            [['deviceId', 'impactId', 'description', 'hidden', 'createdAt', 'updatedAt'], 'safe'],
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
        $query = DeviceToImpact::find()
            ->with(['impactRelation'])
            ->hidden()
            ->andWhere([DeviceToImpact::tableName() . '.[[deviceId]]' => $this->deviceId]);

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

        $query->andFilterWhere(['like', DeviceToImpact::tableName() . '.[[description]]', $this->description])
        ->andFilterWhere([DeviceToImpact::tableName() . '.[[status]]' => $this->status])
        ->andFilterWhere([DeviceToImpact::tableName() . '.[[hidden]]' => $this->hidden]);

        return $dataProvider;
    }
}
