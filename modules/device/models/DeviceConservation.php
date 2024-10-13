<?php

namespace app\modules\device\models;

use app\common\database\traits\HiddenAttributeTrait;
use app\common\interface\HiddenAttributeInterface;
use app\modules\device\models\query\DeviceConservationQuery;
use app\modules\device\models\query\DeviceQuery;
use kilyanov\behaviors\ActiveRecord;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%device_conservation}}".
 *
 * @property string $id ID
 * @property string $deviceId СИ
 * @property string $conservation_date Дата консервации
 * @property string $reConservation_date Дата переконсервации
 * @property string|null $description Причина
 * @property int $hidden
 * @property string $createdAt
 * @property string $updatedAt
 *
 * @property Device $device
 */
class DeviceConservation extends ActiveRecord implements HiddenAttributeInterface
{
    use HiddenAttributeTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%device_conservation}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['deviceId', 'conservation_date', 'reConservation_date',], 'required'],
            [['conservation_date', 'reConservation_date', 'createdAt', 'updatedAt'], 'safe'],
            [['description'], 'string'],
            [['hidden'], 'integer'],
            [
                ['description',],
                'trim',
                'when' => function ($model, $attribute) {
                    return !empty($model->$attribute);
                }
            ],
            [
                ['deviceId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Device::class,
                'targetAttribute' => ['deviceId' => 'id']
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'deviceId' => 'СИ',
            'conservation_date' => 'Дата консервации',
            'reConservation_date' => 'Дата переконсервации',
            'description' => 'Причина',
            'hidden' => 'Hidden',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Device]].
     *
     * @return ActiveQuery|DeviceQuery
     */
    public function getDevice(): ActiveQuery|DeviceQuery
    {
        return $this->hasOne(Device::class, ['id' => 'deviceId']);
    }

    /**
     * {@inheritdoc}
     * @return DeviceConservationQuery the active query used by this AR class.
     */
    public static function find(): DeviceConservationQuery
    {
        return new DeviceConservationQuery(get_called_class());
    }
}
