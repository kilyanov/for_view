<?php

declare(strict_types=1);

namespace app\modules\device\models\search;

use app\modules\device\models\DeviceRejection;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class DeviceRejectionSearch extends DeviceRejection
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            ['hidden', 'integer'],
            ['id', 'string'],
            [['deviceId', 'rejection_date', 'hidden', 'createdAt', 'updatedAt', 'comment', 'status'], 'safe'],
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
        $query = DeviceRejection::find()
            ->hidden()
            ->andWhere([DeviceRejection::tableName() . '.[[deviceId]]' => $this->deviceId]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'rejection_date' => SORT_DESC,
                ]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        if (!empty($this->verification_date)) {
            $query->andWhere(['like', DeviceRejection::tableName() . '.[[rejection_date]]', $this->rejection_date]);
        }

        return $dataProvider;
    }
}
