<?php

declare(strict_types=1);

namespace app\modules\device\models;

use app\common\database\traits\HiddenAttributeTrait;
use app\common\interface\HiddenAttributeInterface;
use app\modules\device\behaviors\StatusBehavior;
use app\modules\device\interface\StatusAttributeInterface;
use app\modules\device\models\query\DeviceQuery;
use app\modules\device\models\query\DeviceToImpactQuery;
use app\modules\device\trait\StatusAttributeTrait;
use app\modules\impact\models\Impact;
use app\modules\impact\models\query\ImpactQuery;
use kilyanov\behaviors\ActiveRecord;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%device_to_impact}}".
 *
 * @property string $id ID
 * @property string $deviceId СИ
 * @property string $impactId Вид воздействия
 * @property string|null $description Причина
 * @property int $hidden
 * @property string|null $status Статус
 * @property string $createdAt
 * @property string $updatedAt
 *
 * @property Device $deviceRelation
 * @property Impact $impactRelation
 */
class DeviceToImpact extends ActiveRecord implements HiddenAttributeInterface, StatusAttributeInterface
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
        return '{{%device_to_impact}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['deviceId', 'impactId',], 'required'],
            [['description'], 'string'],
            [['hidden'], 'integer'],
            [['createdAt', 'updatedAt'], 'safe'],
            [
                ['deviceId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Device::class,
                'targetAttribute' => ['deviceId' => 'id']
            ],
            [
                ['impactId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Impact::class,
                'targetAttribute' => ['impactId' => 'id']
            ],
            ['hidden', 'default', 'value' => HiddenAttributeInterface::HIDDEN_NO],
            ['description', 'default', 'value' => null],
            [
                ['description'],
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
            'impactId' => 'Вид воздействия',
            'description' => 'Причина',
            'hidden' => 'Скрыт',
            'status' => 'Статус',
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
     * Gets query for [[Impact]].
     *
     * @return ActiveQuery|ImpactQuery
     */
    public function getImpactRelation(): ImpactQuery|ActiveQuery
    {
        return $this->hasOne(Impact::class, ['id' => 'impactId']);
    }

    /**
     * {@inheritdoc}
     * @return DeviceToImpactQuery the active query used by this AR class.
     */
    public static function find(): DeviceToImpactQuery
    {
        return new DeviceToImpactQuery(get_called_class());
    }
}
