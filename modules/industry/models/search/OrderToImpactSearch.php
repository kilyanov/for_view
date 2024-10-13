<?php

declare(strict_types=1);

namespace app\modules\industry\models\search;

use app\modules\industry\models\OrderToImpact;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class OrderToImpactSearch extends OrderToImpact
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
            [['orderId', 'impactId', 'comment', 'hidden'], 'safe'],
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
        $query = OrderToImpact::find()
            ->with(['impactRelation', 'orderRelation'])
            ->andWhere([OrderToImpact::tableName() . '.[[orderId]]' => $this->orderId]);

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

        $query->andFilterWhere([OrderToImpact::tableName() . '.[[impactId]]' => $this->impactId])
            ->andFilterWhere([OrderToImpact::tableName() . '.[[hidden]]' => $this->hidden]);

        return $dataProvider;
    }
}
