<?php

declare(strict_types=1);

namespace app\modules\device\models\search;

use app\modules\device\models\DeviceStandard;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class DeviceStandardSearch extends DeviceStandard
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            ['hidden', 'integer'],
            ['id', 'string'],
            [['deviceId', 'numberStandard', 'hidden', 'createdAt', 'updatedAt'], 'safe'],
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
        $query = DeviceStandard::find()
            ->hidden()
            ->andWhere([DeviceStandard::tableName() . '.[[deviceId]]' => $this->deviceId]);

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

        $query->andFilterWhere([DeviceStandard::tableName() . '.[[status]]' => $this->status])
            ->andFilterWhere([DeviceStandard::tableName() . '.[[hidden]]' => $this->hidden]);

        return $dataProvider;
    }
}
