<?php

declare(strict_types=1);

namespace app\modules\device\models;

use app\common\database\traits\HiddenAttributeTrait;
use app\common\interface\HiddenAttributeInterface;
use app\modules\device\behaviors\StatusBehavior;
use app\modules\device\interface\StatusAttributeInterface;
use app\modules\device\models\query\DeviceQuery;
use app\modules\device\models\query\DeviceStandardQuery;
use app\modules\device\trait\StatusAttributeTrait;
use kilyanov\behaviors\ActiveRecord;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%device_standard}}".
 *
 * @property string $id ID
 * @property string $deviceId СИ
 * @property string $numberStandard Номер эталона
 * @property string|null $status Статус
 * @property int $hidden
 * @property string $createdAt
 * @property string $updatedAt
 *
 * @property Device $deviceRelation
 */
class DeviceStandard extends ActiveRecord implements HiddenAttributeInterface, StatusAttributeInterface
{
    use HiddenAttributeTrait;
    use StatusAttributeTrait;

    /**
     * @return array
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        return ArrayHelper::merge(
            $behaviors,
            [
                'StatusBehavior' => [
                    'class' => StatusBehavior::class
                ]
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%device_standard}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['deviceId', 'numberStandard', ], 'required'],
            [['hidden'], 'integer'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['numberStandard',], 'string', 'max' => 255],
            [
                ['deviceId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Device::class,
                'targetAttribute' => ['deviceId' => 'id']
            ],
            ['hidden', 'default', 'value' => HiddenAttributeInterface::HIDDEN_NO],
            [
                ['numberStandard'],
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
            'deviceId' => 'СИ',
            'numberStandard' => 'Номер эталона',
            'status' => 'Статус',
            'hidden' => 'Скрыт',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Device]].
     *
     * @return ActiveQuery|DeviceQuery
     */
    public function getDeviceRelation(): ActiveQuery|DeviceQuery
    {
        return $this->hasOne(Device::class, ['id' => 'deviceId']);
    }

    /**
     * {@inheritdoc}
     * @return DeviceStandardQuery the active query used by this AR class.
     */
    public static function find(): DeviceStandardQuery
    {
        return new DeviceStandardQuery(get_called_class());
    }
}
