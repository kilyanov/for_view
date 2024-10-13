<?php

declare(strict_types=1);

namespace app\modules\device\models;

use app\common\database\traits\HiddenAttributeTrait;
use app\common\interface\HiddenAttributeInterface;
use app\modules\device\behaviors\StatusBehavior;
use app\modules\device\interface\StatusAttributeInterface;
use app\modules\device\models\query\DeviceQuery;
use app\modules\device\models\query\DeviceToUnitQuery;
use app\modules\device\trait\StatusAttributeTrait;
use app\modules\unit\models\query\UnitQuery;
use app\modules\unit\models\Unit;
use kilyanov\behaviors\ActiveRecord;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%device_to_unit}}".
 *
 * @property string $id ID
 * @property string $deviceId СИ
 * @property string $unitId Подразделение
 * @property string|null $description Причина передачи
 * @property string|null $status Статус
 * @property int $hidden
 * @property string $createdAt
 * @property string $updatedAt
 *
 * @property Device $deviceRelation
 * @property Unit $unitRelation
 */
class DeviceToUnit extends ActiveRecord implements HiddenAttributeInterface, StatusAttributeInterface
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
        return '{{%device_to_unit}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['deviceId', 'unitId',], 'required'],
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
                ['unitId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Unit::class,
                'targetAttribute' => ['unitId' => 'id']
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
            'unitId' => 'Подразделение',
            'description' => 'Причина передачи',
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
     * Gets query for [[Unit]].
     *
     * @return ActiveQuery|UnitQuery
     */
    public function getUnitRelation(): ActiveQuery|UnitQuery
    {
        return $this->hasOne(Unit::class, ['id' => 'unitId']);
    }

    /**
     * {@inheritdoc}
     * @return DeviceToUnitQuery the active query used by this AR class.
     */
    public static function find(): DeviceToUnitQuery
    {
        return new DeviceToUnitQuery(get_called_class());
    }
}
