<?php

declare(strict_types=1);

namespace app\modules\device\models\search;

use app\common\interface\HiddenAttributeInterface;
use app\modules\device\models\DeviceName;
use app\modules\device\models\DeviceProperty;
use app\modules\device\models\DeviceType;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class DevicePropertySearch extends DeviceProperty
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
            [['name', 'status', 'createdAt', 'updatedAt', 'deviceNameId', 'deviceTypeId', 'description'], 'safe'],
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
        $query = DeviceProperty::find()->hidden()
            ->joinWith(['deviceNameRelation.deviceTypeRelation']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        if (!empty($this->deviceNameId)) {
            $query->andWhere(['like', DeviceName::tableName() . '.[[name]]', $this->deviceNameId])
                ->andWhere([DeviceName::tableName() . '.[[hidden]]' => HiddenAttributeInterface::HIDDEN_NO]);
        }

        if (!empty($this->deviceTypeId)) {
            $query->andWhere(['like', DeviceType::tableName() . '.[[name]]', $this->deviceTypeId])
                ->andWhere([DeviceType::tableName() . '.[[hidden]]' => HiddenAttributeInterface::HIDDEN_NO]);
        }

        $query->andFilterWhere(['like', DeviceProperty::tableName() . '.[[name]]', $this->name])
            ->andFilterWhere([DeviceProperty::tableName() . '.[[status]]' => $this->status])
            ->andFilterWhere(['like', DeviceProperty::tableName() . '.[[description]]', $this->description])
            ->andFilterWhere([DeviceProperty::tableName() . '.[[hidden]]' => $this->hidden]);

        return $dataProvider;
    }
}
