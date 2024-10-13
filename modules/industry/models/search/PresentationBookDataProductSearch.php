<?php

declare(strict_types=1);

namespace app\modules\industry\models\search;

use app\modules\industry\models\PresentationBookDataProduct;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class PresentationBookDataProductSearch extends PresentationBookDataProduct
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            ['id', 'string'],
            [
                ['bookId', 'norma'],
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
        $query = PresentationBookDataProduct::find()->with([
            'bookRelation', 'orderRationingRelation', 'orderRationingDataRelation'
        ])
            ->andWhere(['bookId' => $this->bookId]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['norma' => $this->norma]);

        return $dataProvider;
    }
}
