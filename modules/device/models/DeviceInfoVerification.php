<?php

declare(strict_types=1);

namespace app\modules\device\models;

use app\common\database\traits\HiddenAttributeTrait;
use app\common\interface\HiddenAttributeInterface;
use app\modules\device\behaviors\StatusBehavior;
use app\modules\device\interface\StatusAttributeInterface;
use app\modules\device\models\query\DeviceInfoVerificationQuery;
use app\modules\device\models\query\DeviceQuery;
use app\modules\device\trait\StatusAttributeTrait;
use kilyanov\behaviors\ActiveRecord;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%device_info_verification}}".
 *
 * @property string $id ID
 * @property string $deviceId СИ
 * @property string|null $linkView Сведения о результатах поверки СИ
 * @property string|null $linkBase Сведения о регистрационном номере типа СИ
 * @property string|null $certificateNumber Номер свидетельства/Номер извещения
 * @property string|null $status Статус
 * @property int $hidden
 * @property string $createdAt
 * @property string $updatedAt
 * @property Device $deviceRelation
 *
 *  @property-read DeviceQuery|ActiveQuery $device
 *  @property-read null|string|array|float $fullName
 */
class DeviceInfoVerification extends ActiveRecord implements HiddenAttributeInterface, StatusAttributeInterface
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
        return '{{%device_info_verification}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['deviceId', ], 'required'],
            [['linkView', 'linkBase', 'certificateNumber'], 'string'],
            [['hidden'], 'integer'],
            [['createdAt', 'updatedAt'], 'safe'],
            [
                ['deviceId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Device::class,
                'targetAttribute' => ['deviceId' => 'id']
            ],
            ['hidden', 'default', 'value' => HiddenAttributeInterface::HIDDEN_NO],
            [
                ['linkView', 'linkBase', 'certificateNumber'],
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
            'linkView' => 'Сведения о результатах поверки СИ',
            'linkBase' => 'Сведения о регистрационном номере типа СИ',
            'certificateNumber' => 'Номер свидетельства/Номер извещения',
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
    public function getDevice(): ActiveQuery|DeviceQuery
    {
        return $this->hasOne(Device::class, ['id' => 'deviceId']);
    }

    /**
     * {@inheritdoc}
     * @return ActiveQuery|DeviceInfoVerificationQuery the active query used by this AR class.
     */
    public static function find(): ActiveQuery|DeviceInfoVerificationQuery
    {
        return new DeviceInfoVerificationQuery(get_called_class());
    }

    /**
     * @return array|string|float|null
     */
    public function getFullName(): array|string|float|null
    {
        return '';
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
     */
    public static function asDropDown(array $config = []): array
    {
        return [];
    }
}
