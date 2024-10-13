<?php

declare(strict_types=1);

namespace app\modules\application\models\search;

use app\modules\application\models\Application;
use app\modules\application\models\ApplicationData;
use app\modules\resource\models\Resource;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class ApplicationDataSearch extends ApplicationData
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
            ['id', 'string'],
            [[
                'applicationId', 'resourceId', 'quantity', 'mark', 'type',
                'comment', 'deliveryTime', 'quantityReceipt', 'receiptDate', 'status',
                'designation'
            ], 'safe'],
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
        $query = ApplicationData::find()
            ->joinWith(['resourceRelation', 'applicationRelation'])
            ->where([ApplicationData::tableName() . '.[[applicationId]]' => $this->applicationId]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        $dataProvider->pagination = [
            'pageSize' => 1500,
        ];

        if (!$this->validate()) {
            return $dataProvider;
        }

        if (!empty($this->resourceId)) {
            $query->andFilterWhere(['like', Resource::tableName() . '.[[name]]', $this->resourceId]);
        }
        if (!empty($this->quantityReceipt)) {
            $query->andFilterWhere([ApplicationData::tableName() . '.[[quantityReceipt]]' => $this->quantityReceipt]);
        }
        if (!empty($this->comment)) {
            $query->andFilterWhere([
                'or',
                ['like', ApplicationData::tableName() . '.[[comment]]', $this->comment],
                ['like', Application::tableName() . '.[[comment]]', $this->comment],
            ]);
        }

        return $dataProvider;
    }
}
