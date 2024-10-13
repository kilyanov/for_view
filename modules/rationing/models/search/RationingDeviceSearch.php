<?php

declare(strict_types=1);

namespace app\modules\rationing\models\search;

use app\modules\rationing\models\RationingDevice;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class RationingDeviceSearch extends RationingDevice
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
            [
                ['paragraph', 'name', 'norma',],
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
        $query = RationingDevice::find()
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
        }

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', RationingDevice::tableName() . '.[[name]]', $this->name])
            ->andFilterWhere([RationingDevice::tableName() . '.[[paragraph]]' => $this->paragraph])
            ->andFilterWhere([RationingDevice::tableName() . '.[[norma]]' => $this->norma]);

        return $dataProvider;
    }
}
