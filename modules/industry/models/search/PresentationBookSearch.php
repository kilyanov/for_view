<?php

declare(strict_types=1);

namespace app\modules\industry\models\search;

use app\modules\industry\models\OrderList;
use app\modules\industry\models\PresentationBook;
use app\modules\personal\models\Personal;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

class PresentationBookSearch extends PresentationBook
{
    public const TYPE_COUNT_PRESENT = 0;
    public const TYPE_COUNT_NORMA = 1;

    /**
     * @var int
     */
    public int $typeView = self::TYPE_COUNT_PRESENT;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            ['id', 'string'],
            [
                [
                    'groupId', 'orderId', 'typeOrder', 'personalId',
                    'impactId', 'unitId', 'name', 'number', 'inventoryNumber',
                    'date', 'year', 'month', 'norma', 'comment', 'status',
                    'unitGroup', 'typeView'
                ],
                'safe'
            ],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        $labels = parent::attributeLabels();

        return ArrayHelper::merge(
            ['typeView' => 'Тип'],
            $labels
        );
    }

    /**
     * @return string[]
     */
    public static function getListTypeView(): array
    {
        return [
            self::TYPE_COUNT_PRESENT => 'По кол-во предъявлений',
            self::TYPE_COUNT_NORMA => 'По нормо-часам'
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
        $query = PresentationBook::find()->joinWith([
            'impactRelation', 'orderRelation', 'deviceRepairRelation', 'deviceVerificationRelation',
            'personalRelation', 'presentationBookDataDeviceRepairsRelation', 'unitRelation',
            'presentationBookDataProductsRelation', 'standRelation', 'groupRelation'
        ]);
        $query->andFilterWhere([PresentationBook::tableName() . '.[[groupId]]' => $this->groupId]);


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
            'sort' => [
                'defaultOrder' => [
                    'date' => SORT_ASC,
                ]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        if (!empty($this->year)) {
            $query->andFilterWhere([PresentationBook::tableName() . '.[[year]]' => $this->year]);
        }
        else {
            $query->andWhere([PresentationBook::tableName() . '.[[year]]' => date('Y')]);
        }

        if (!empty($this->month)) {
            $query->andFilterWhere([PresentationBook::tableName() . '.[[month]]' => $this->month]);
        }
        else {
            $query->andWhere([PresentationBook::tableName() . '.[[month]]' => (int)date('m')]);
        }

        $query->andFilterWhere(['like', PresentationBook::tableName() . '.[[number]]', $this->number])
            ->andFilterWhere(['like', PresentationBook::tableName() . '.[[name]]', $this->name])
            ->andFilterWhere(['like', PresentationBook::tableName() . '.[[inventoryNumber]]', $this->inventoryNumber])
            ->andFilterWhere(['like', PresentationBook::tableName() . '.[[date]]', $this->date])
            ->andFilterWhere(['like', PresentationBook::tableName() . '.[[comment]]', $this->comment])
            ->andFilterWhere([PresentationBook::tableName() . '.[[norma]]' => $this->norma])
            ->andFilterWhere(['like', Personal::tableName() . '.[[fistName]]', $this->personalId])
            ->andFilterWhere([PresentationBook::tableName() . '.[[impactId]]' => $this->impactId])
            ->andFilterWhere([PresentationBook::tableName() . '.[[unitId]]' => $this->unitId])
            ->andFilterWhere([PresentationBook::tableName() . '.[[month]]' => $this->month])
            ->andFilterWhere([PresentationBook::tableName() . '.[[typeOrder]]' => $this->typeOrder])
            ->andFilterWhere([PresentationBook::tableName() . '.[[status]]' => $this->status])
            ->andFilterWhere(['like', OrderList::tableName() . '.[[number]]', $this->orderId]);

        return $dataProvider;
    }
}
