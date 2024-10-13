<?php

declare(strict_types=1);

namespace app\modules\nso\models\search;

use app\modules\nso\models\StandDateService;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class StandDateServiceSearch extends StandDateService
{

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            ['hidden', 'integer'],
            ['id', 'string'],
            [
                [
                    'standId',
                    'dateService',
                    'comment',
                ],
                'safe'
            ],
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
        $query = StandDateService::find()->hidden()
            ->andWhere(['standId' => $this->standId])->limit(20);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'dateService' => SORT_DESC,
                ]
            ]
        ]);

        $this->load($params);

        return $dataProvider;
    }
}
