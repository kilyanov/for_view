<?php

declare(strict_types=1);

namespace app\modules\device\models;

use app\common\database\traits\HiddenAttributeTrait;
use app\common\interface\HiddenAttributeInterface;
use app\modules\device\behaviors\DevicePropertyBehavior;
use app\modules\device\interface\StatusAttributeInterface;
use app\modules\device\models\query\DevicePropertyQuery;
use app\modules\device\trait\StatusAttributeTrait;
use Exception;
use kilyanov\behaviors\ActiveRecord;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%device_property}}".
 *
 * @property string $id ID
 * @property string|null $deviceNameId Тип
 * @property string $name Наименование
 * @property string|null $status Статус
 * @property string|null $description Примечание
 * @property int $hidden
 * @property string $createdAt
 * @property string $updatedAt
 * @property string|null $deviceTypeId Тип
 *
 * @property DeviceName $deviceNameRelation
 * @property Device[] $devicesRelation
 *
 * @property-read null|string|array|float $fullName
 */
class DeviceProperty extends ActiveRecord implements HiddenAttributeInterface, StatusAttributeInterface
{
    use HiddenAttributeTrait;
    use StatusAttributeTrait;

    /**
     * @var string|null
     */
    public ?string $deviceTypeId = null;

    /**
     * @return array
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        return ArrayHelper::merge(
            $behaviors,
            [
                'DevicePropertyBehavior' => [
                    'class' => DevicePropertyBehavior::class
                ]
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%device_property}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name',], 'required'],
            [['description'], 'string'],
            [['hidden'], 'integer'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [
                ['deviceNameId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => DeviceName::class,
                'targetAttribute' => ['deviceNameId' => 'id']
            ],
            ['hidden', 'default', 'value' => HiddenAttributeInterface::HIDDEN_NO],
            ['description', 'default', 'value' => null],
            [
                ['name', 'description'],
                'trim',
                'when' => function ($model, $attribute) {
                    return !empty($model->$attribute);
                }
            ],
            ['status', 'in', 'range' => array_keys(self::getStatusList())],
            ['status', 'default', 'value' => StatusAttributeInterface::STATUS_ACTIVE],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'deviceNameId' => 'Наименование',
            'name' => 'Характеристика',
            'status' => 'Статус',
            'description' => 'Примечание',
            'hidden' => 'Скрыт',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
            /** Виртуальные поля */
            'deviceTypeId' => 'Тип'
        ];
    }

    /**
     * Gets query for [[DeviceName]].
     *
     * @return ActiveQuery
     */
    public function getDeviceNameRelation(): ActiveQuery
    {
        return $this->hasOne(DeviceName::class, ['id' => 'deviceNameId']);
    }

    /**
     * Gets query for [[Devices]].
     *
     * @return ActiveQuery
     */
    public function getDevicesRelation(): ActiveQuery
    {
        return $this->hasMany(Device::class, ['devicePropertyId' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return DevicePropertyQuery the active query used by this AR class.
     */
    public static function find(): DevicePropertyQuery
    {
        return new DevicePropertyQuery(get_called_class());
    }

    /**
     * @return array|string|float|null
     */
    public function getFullName(): array|string|float|null
    {
        return implode(', ', array_filter([$this->name]));
    }

    /**
     * @param array $model
     * @return string
     */
    public static function getFullNameMoving(array $model): string
    {
        return '';
    }

    /**
     * @param array $config
     * @return array
     * @throws Exception
     */
    public static function asDropDown(array $config = []): array
    {
        /** @var self $query */
        $query = self::find()->hidden();

        return $query->status(ArrayHelper::getValue($config, 'status'))->asDropDown();
    }
}
