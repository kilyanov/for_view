<?php

declare(strict_types=1);

namespace app\modules\device\models\search;

use app\modules\device\interface\StatusAttributeInterface;
use app\modules\device\models\Device;
use app\modules\device\models\DeviceName;
use app\modules\device\models\DeviceProperty;
use app\modules\device\models\DeviceStandard;
use app\modules\device\models\DeviceToUnit;
use app\modules\device\models\DeviceType;
use app\modules\device\models\DeviceVerification;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

class DeviceSearch extends Device
{
    /**
     * @var int|bool|null
     */
    public int|bool|null $pageLimit = null;

    /**
     * @var int
     */
    public int $standardShow = 0;

    /**
     * @var string|null
     */
    public ?string $dateVerificationStart = null;

    /**
     * @var string|null
     */
    public ?string $dateVerificationEnd = null;

    /**
     * @var string|null
     */
    public ?string $dateVerification = null;

    /**
     * @var string|null
     */
    public ?string $dateVerificationStartNext = null;

    /**
     * @var string|null
     */
    public ?string $dateVerificationEndNext = null;

    /**
     * @var string|null
     */
    public ?string $dateVerificationNext = null;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['hidden',], 'integer'],
            [['id',], 'string'],
            [[
                'deviceGroupId', 'deviceTypeId',
                'deviceNameId', 'devicePropertyId',
                'stateRegister', 'factoryNumber',
                'inventoryNumber', 'verificationPeriod',
                'norma', 'category', 'description', 'yearRelease', 'linkView',
                'linkBase', 'status', 'hidden', 'createdAt', 'updatedAt',
                /** Виртуальные поля */
                // 'verification', 'verificationNext', 'reject',
                'unitId', 'standardShow', 'pageLimit',
                'dateVerificationStart', 'dateVerificationEnd', 'dateVerification',
                'dateVerificationStartNext', 'dateVerificationEndNext', 'dateVerificationNext'
            ], 'safe'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        $attributeLabels = parent::attributeLabels();

        return ArrayHelper::merge([
            'standardShow' => 'Эталоны'
        ], $attributeLabels);
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
     * @throws InvalidConfigException
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = Device::find()
            ->with(
                [
                    'deviceToUnitRelation.unitRelation',
                    'deviceVerificationRelation',
                    'deviceRejectionRelation',
                    'deviceInfoVerificationRelation',
                    'deviceStandardRelation',
                    'deviceVerificationRelation'
                ],
            );

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'dateVerification' => [
                        'asc' => [DeviceVerification::tableName() . '.{{verification_date}}' => SORT_ASC],
                        'desc' => [DeviceVerification::tableName() . '.{{verification_date}}' => SORT_DESC],
                    ],
                    'nextVerification_date' => [
                        'asc' => [DeviceVerification::tableName() . '.{{nextVerification_date}}' => SORT_ASC],
                        'desc' => [DeviceVerification::tableName() . '.{{nextVerification_date}}' => SORT_DESC],
                    ],
                    'updatedAt' => [
                        'asc' => [Device::tableName() . '.{{updatedAt}}' => SORT_ASC],
                        'desc' => [Device::tableName() . '.{{updatedAt}}' => SORT_DESC],
                    ]
                ],
                'defaultOrder' => [
                    'updatedAt' => SORT_ASC
                ]
            ]
        ]);

        $this->load($params);

        if ($this->pageLimit === false) {
            $dataProvider->pagination = false;
        } else {
            $dataProvider->pagination = [
                'pageSize' => $this->pageLimit,
            ];
        }

        if (!$this->validate()) {
            return $dataProvider;
        }

        if (!empty($this->inventoryNumber)) {
            $query->andWhere([Device::tableName() . '.[[inventoryNumber]]' => $this->inventoryNumber]);
        }
        if (!empty($this->deviceGroupId)) {
            $query->joinWith(['deviceGroupRelation']);
            $query->andWhere([Device::tableName() . '.[[deviceGroupId]]' => $this->deviceGroupId]);
        }
        if (!empty($this->deviceTypeId)) {
            $query->joinWith(['deviceTypeRelation']);
            $query->andWhere(['like', DeviceType::tableName() . '.[[name]]', $this->deviceTypeId]);
        }
        if (!empty($this->deviceNameId)) {
            $query->joinWith(['deviceNameRelation']);
            $query->andWhere(['like', DeviceName::tableName() . '.[[name]]', $this->deviceNameId]);
        }
        if (!empty($this->devicePropertyId)) {
            $query->joinWith(['devicePropertyRelation']);
            $query->andWhere(['like', DeviceProperty::tableName() . '.[[name]]', $this->devicePropertyId]);
        }
        if (!empty($this->unitId)) {
            $query->joinWith(['deviceToUnitRelation']);
            $query->andWhere([DeviceToUnit::tableName() . '.[[unitId]]' => $this->unitId]);
        }
        if (!empty($this->verificationNext)) {
            $query->joinWith(['deviceVerificationsRelation']);
            $query->andFilterWhere([
                DeviceVerification::tableName() . '.[[nextVerification_date]]' => Yii::$app->formatter->asDate($this->verificationNext, 'php:Y-m-d')
            ]);
        }
        if (!empty($this->dateVerification)) {
            $query->joinWith(['deviceVerificationsRelation']);
            $query->andFilterWhere([
                '<=',
                DeviceVerification::tableName() . '.[[verification_date]]',
                Yii::$app->formatter->asDate($this->dateVerification, 'php:Y-m-d')
            ]);
            $query->andFilterWhere([DeviceVerification::tableName() . '.[[status]]' => StatusAttributeInterface::STATUS_ACTIVE]);
            $dataProvider->sort->defaultOrder = ['dateVerification' => SORT_DESC];
        }
        if (!empty($this->dateVerificationNext)) {
            $query->joinWith(['deviceVerificationsRelation']);
            $query->andFilterWhere([
                '<=',
                DeviceVerification::tableName() . '.[[nextVerification_date]]',
                Yii::$app->formatter->asDate($this->dateVerificationNext, 'php:Y-m-d')
            ]);
            $query->andFilterWhere([DeviceVerification::tableName() . '.[[status]]' => StatusInterface::STATUS_ACTIVE]);
            $dataProvider->sort->defaultOrder = ['nextVerification_date' => SORT_DESC];
        }
        if (!empty($this->dateVerificationStart) || !empty($this->dateVerificationEnd)) {
            $query->joinWith(['deviceVerificationsRelation']);
            $query->andFilterWhere([
                '>=',
                DeviceVerification::tableName() . '.[[verification_date]]',
                $this->dateVerificationStart
            ]);
            $query->andFilterWhere([
                '<=',
                DeviceVerification::tableName() . '.[[verification_date]]',
                $this->dateVerificationEnd
            ]);
            $query->andFilterWhere([DeviceVerification::tableName() . '.[[status]]' => StatusAttributeInterface::STATUS_ACTIVE]);
            $dataProvider->sort->defaultOrder = ['dateVerification' => SORT_DESC];
        }
        if (!empty($this->dateVerificationStartNext) || !empty($this->dateVerificationEndNext)) {
            $query->joinWith(['deviceVerificationsRelation']);
            $query->andFilterWhere([
                '>=',
                DeviceVerification::tableName() . '.[[nextVerification_date]]',
                $this->dateVerificationStartNext
            ]);
            $query->andFilterWhere([
                '<=',
                DeviceVerification::tableName() . '.[[nextVerification_date]]',
                $this->dateVerificationEndNext
            ]);
            $query->andFilterWhere([DeviceVerification::tableName() . '.[[status]]' => StatusAttributeInterface::STATUS_ACTIVE]);
            $dataProvider->sort->defaultOrder = ['nextVerification_date' => SORT_DESC];
        }
        $query->andFilterWhere(['like', Device::tableName() . '.[[stateRegister]]', $this->stateRegister])
            ->andFilterWhere(['like', Device::tableName() . '.[[factoryNumber]]', $this->factoryNumber])
            ->andFilterWhere([Device::tableName() . '.[[category]]' => $this->category])
            ->andFilterWhere([Device::tableName() . '.[[norma]]' => $this->norma])
            ->andFilterWhere(['like', Device::tableName() . '.[[description]]', $this->description])
            ->andFilterWhere([Device::tableName() . '.[[yearRelease]]' => $this->yearRelease])
            ->andFilterWhere([Device::tableName() . '.[[status]]' => $this->status])
            ->andFilterWhere([Device::tableName() . '.[[hidden]]' => $this->hidden])
            ->andFilterWhere([Device::tableName() . '.[[verificationPeriod]]' => $this->verificationPeriod]);

        if ($this->standardShow === 1) {
            $queryExist = DeviceStandard::find()->select('deviceId');
            $query->andWhere([Device::tableName() . '.[[id]]' => $queryExist]);
        }

        return $dataProvider;
    }
}
