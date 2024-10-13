<?php

declare(strict_types=1);

namespace app\modules\application\models\search;

use app\modules\application\models\Application;
use app\modules\industry\models\OrderList;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * @property string|null $contractId Контракт
 */
class ApplicationSearch extends Application
{
    /**
     * @var int|bool|null
     */
    public int|bool|null $pageLimit = null;

    /**
     * @var string|null
     */
    public ?string $contractId = null;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            ['id', 'string'],
            [['orderId', 'productId', 'unitId', 'number', 'dateFiling', 'comment', 'contractId'], 'safe'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        $attributeLabels = parent::attributeLabels();
        return ArrayHelper::merge($attributeLabels, [
            'contractId' => 'Контракт'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios(): array
    {
        return Model::scenarios();
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = Application::find()
            ->joinWith(['orderRelation', 'unitRelation', 'productRelation'])
            ->hidden();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'dateFiling' => SORT_DESC,
                ]
            ]
        ]);

        if ($this->pageLimit === false) {
            $dataProvider->pagination = false;
        } else {
            $dataProvider->pagination = [
                'pageSize' => $this->pageLimit,
            ];
        }
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andFilterWhere([Application::tableName() . '.[[unitId]]' => $this->unitId])
            ->andFilterWhere([Application::tableName() . '.[[number]]' => $this->number])
            ->andFilterWhere(['like', Application::tableName() . '.[[comment]]', $this->comment])
            ->andFilterWhere([OrderList::tableName() . '.[[number]]' => $this->orderId])
            ->andFilterWhere([OrderList::tableName() . '.[[contractId]]' => $this->contractId]);

        return $dataProvider;
    }
}
