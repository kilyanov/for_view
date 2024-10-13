<?php

declare(strict_types=1);

namespace app\modules\device\models\search;

use app\modules\device\models\DeviceType;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class DeviceTypeSearch extends DeviceType
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
        $query = DeviceType::find()->hidden();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'name' => SORT_ASC,
                ]
            ]
        ]);

		if ($this->pageLimit === true) {
			$dataProvider->pagination = false;
		}

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['status' => $this->status])
            ->andFilterWhere(['hidden' => $this->hidden])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
