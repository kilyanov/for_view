<?php

declare(strict_types=1);

namespace app\modules\device\models\search;

use app\common\interface\HiddenAttributeInterface;
use app\modules\device\models\DeviceName;
use app\modules\device\models\DeviceType;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class DeviceNameSearch extends DeviceName
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
            [['name', 'status', 'createdAt', 'updatedAt', 'deviceTypeId', 'description'], 'safe'],
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
        $query = DeviceName::find()->hidden();

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

        if (!empty($this->deviceTypeId)) {
            $query->joinWith(['deviceTypeRelation']);
            $query->andWhere(['like', DeviceType::tableName() . '.[[name]]', $this->deviceTypeId])
                ->andWhere([DeviceType::tableName() . '.[[hidden]]' => HiddenAttributeInterface::HIDDEN_NO]);
        }

        $query->andFilterWhere(['like', DeviceName::tableName() . '.[[name]]', $this->name])
            ->andFilterWhere([DeviceName::tableName() . '.[[status]]' => $this->status])
            ->andFilterWhere([DeviceName::tableName() . '.[[hidden]]' => $this->hidden])
            ->andFilterWhere(['like', DeviceName::tableName() . '.[[description]]', $this->description]);

        return $dataProvider;
    }
}
