<?php

declare(strict_types=1);

namespace app\modules\rationing\models\search;

use app\modules\rationing\models\RationingDeviceData;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class RationingDeviceDataSearch extends RationingDeviceData
{
    /**
     * @var int|bool|null
     */
    public int|bool|null $pageLimit = 250;

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
                    'operationNumber',
                    'rationingDeviceId',
                    'name',
                    'unitId',
                    'specialId',
                    'ed',
                    'countItems',
                    'periodicity',
                    'category',
                    'norma'
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
        $query = RationingDeviceData::find()
            ->joinWith(['rationingDeviceRelation'])
            ->andFilterWhere([RationingDeviceData::tableName() . '.[[rationingDeviceId]]' => $this->rationingDeviceId])
            ->hidden();

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
        } else {
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'sort' => [
                    'defaultOrder' => [
                        'sort' => SORT_ASC,
                    ]
                ],
                'pagination' => is_int($this->pageLimit) ? [
                    'params' => $params,
                    'pageSizeLimit' => $this->pageLimit
                ] : false,
            ]);
        }

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', RationingDeviceData::tableName() . '.[[name]]', $this->name])
            ->andFilterWhere([
                RationingDeviceData::tableName() . '.[[category]]' => $this->category,
                RationingDeviceData::tableName() . '.[[unitId]]' => $this->unitId,
                RationingDeviceData::tableName() . '.[[specialId]]' => $this->specialId,
                RationingDeviceData::tableName() . '.[[operationNumber]]' => $this->operationNumber
            ]);

        return $dataProvider;
    }
}
