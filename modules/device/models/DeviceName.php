<?php

declare(strict_types=1);

namespace app\modules\device\models;

use app\common\database\traits\HiddenAttributeTrait;
use app\common\interface\HiddenAttributeInterface;
use app\modules\device\interface\StatusAttributeInterface;
use app\modules\device\models\query\DeviceNameQuery;
use app\modules\device\trait\StatusAttributeTrait;
use Exception;
use kilyanov\behaviors\ActiveRecord;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%device_name}}".
 *
 * @property string $id ID
 * @property string|null $deviceTypeId Тип
 * @property string $name Наименование
 * @property string|null $status Статус
 * @property string|null $description Примечание
 * @property int $hidden
 * @property string $createdAt
 * @property string $updatedAt
 *
 * @property DeviceProperty[] $devicePropertiesRelation
 * @property DeviceType $deviceTypeRelation
 * @property Device[] $devicesRelation
 *
 * @property-read null|string|array|float $fullName
 */
class DeviceName extends ActiveRecord implements HiddenAttributeInterface, StatusAttributeInterface
{
    use HiddenAttributeTrait;
    use StatusAttributeTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%device_name}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', ], 'required'],
            [['description'], 'string'],
            [['hidden'], 'integer'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [
                ['deviceTypeId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => DeviceType::class,
                'targetAttribute' => ['deviceTypeId' => 'id']
            ],
            ['hidden', 'default', 'value' => HiddenAttributeInterface::HIDDEN_NO],
            ['description', 'default', 'value' => null],
            [
                ['name', 'description'],
                'trim',
                'when' => function($model, $attribute) {
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
            'deviceTypeId' => 'Тип',
            'name' => 'Наименование',
            'status' => 'Статус',
            'description' => 'Примечание',
            'hidden' => 'Скрыт',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[DeviceProperties]].
     *
     * @return ActiveQuery
     */
    public function getDevicePropertiesRelation(): ActiveQuery
    {
        return $this->hasMany(DeviceProperty::class, ['deviceNameId' => 'id']);
    }

    /**
     * Gets query for [[DeviceType]].
     *
     * @return ActiveQuery
     */
    public function getDeviceTypeRelation(): ActiveQuery
    {
        return $this->hasOne(DeviceType::class, ['id' => 'deviceTypeId']);
    }

    /**
     * Gets query for [[Devices]].
     *
     * @return ActiveQuery
     */
    public function getDevicesRelation(): ActiveQuery
    {
        return $this->hasMany(Device::class, ['deviceNameId' => 'id']);
    }

    /**
     * @return ActiveQuery|DeviceNameQuery
     */
    public static function find(): ActiveQuery|DeviceNameQuery
    {
        return new DeviceNameQuery(get_called_class());
    }

    /**
     * @return array|string|float|null
     */
    public function getFullName(): array|string|float|null
    {
        return implode(', ', array_filter([$this->name, $this->deviceTypeRelation?->name]));
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
