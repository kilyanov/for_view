<?php

declare(strict_types=1);

namespace app\modules\device\models;

use app\common\database\traits\HiddenAttributeTrait;
use app\common\interface\HiddenAttributeInterface;
use app\modules\device\interface\StatusAttributeInterface;
use app\modules\device\models\query\DeviceTypeQuery;
use app\modules\device\trait\StatusAttributeTrait;
use Exception;
use kilyanov\behaviors\ActiveRecord;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%device_type}}".
 *
 * @property string $id ID
 * @property string $name Название
 * @property string|null $status Статус
 * @property string|null $description Примечание
 * @property int $hidden
 * @property string $createdAt
 * @property string $updatedAt
 *
 * @property DeviceName[] $deviceNames
 * @property Device[] $devices
 *
 * @property-read null|string|array|float $fullName
 */
class DeviceType extends ActiveRecord implements HiddenAttributeInterface, StatusAttributeInterface
{
    use HiddenAttributeTrait;
    use StatusAttributeTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%device_type}}';
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
            [['name',], 'string', 'max' => 255],
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
            'name' => 'Название',
            'status' => 'Статус',
            'description' => 'Примечание',
            'hidden' => 'Скрыт',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[DeviceNames]].
     *
     * @return ActiveQuery
     */
    public function getDeviceNames(): ActiveQuery
    {
        return $this->hasMany(DeviceName::class, ['deviceTypeId' => 'id']);
    }

    /**
     * Gets query for [[Devices]].
     *
     * @return ActiveQuery
     */
    public function getDevices(): ActiveQuery
    {
        return $this->hasMany(Device::class, ['deviceTypeId' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return DeviceTypeQuery the active query used by this AR class.
     */
    public static function find(): DeviceTypeQuery
    {
        return new DeviceTypeQuery(get_called_class());
    }

    /**
     * @return array|string|float|null
     */
    public function getFullName(): array|string|float|null
    {
        return $this->name;
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
