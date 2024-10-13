<?php

declare(strict_types=1);

namespace app\modules\device\models;

use app\common\database\traits\HiddenAttributeTrait;
use app\common\interface\HiddenAttributeInterface;
use app\modules\device\behaviors\DeviceVirtualAttributesBehavior;
use app\modules\device\interface\StatusAttributeInterface;
use app\modules\device\models\query\DeviceQuery;
use Exception;
use kilyanov\behaviors\ActiveRecord;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%device}}".
 *
 * @property string $id ID
 * @property string $deviceGroupId Группа
 * @property string|null $deviceTypeId Тип
 * @property string|null $deviceNameId Наименование
 * @property string|null $devicePropertyId Тех. характ.
 * @property string|null $stateRegister Гос. реестр
 * @property string|null $factoryNumber Зав. номер
 * @property string|null $inventoryNumber Инв. номер
 * @property int|null $verificationPeriod Период
 * @property float|null $norma Н/Ч
 * @property int|null $category Разряд
 * @property string|null $description Комментарии
 * @property int|null $yearRelease Год выпуска
 * @property string|null $status Статус
 * @property int $hidden
 * @property string $createdAt
 * @property string $updatedAt
 *
 * @property DeviceConservation[] $deviceConservationsRelation
 * @property DeviceGroup $deviceGroupRelation
 * @property DeviceInfoVerification $deviceInfoVerificationRelation
 * @property DeviceName $deviceNameRelation
 * @property DeviceProperty $devicePropertyRelation
 * @property DeviceRejection[] $deviceRejectionsRelation
 * @property DeviceRejection $deviceRejectionRelation
 * @property DeviceStandard[] $deviceStandardsRelation
 * @property DeviceStandard $deviceStandardRelation
 * @property DeviceToImpact[] $deviceToImpactsRelation
 * @property DeviceToImpact $deviceToImpactRelation
 * @property DeviceToUnit[] $deviceToUnitsRelation
 * @property DeviceToUnit $deviceToUnitRelation
 * @property DeviceType $deviceTypeRelation
 * @property DeviceVerification[] $deviceVerificationsRelation
 * @property DeviceVerification $deviceVerificationRelation
 *
 * @property-read string $fullInfo
 * @property-read null|string|array|float $fullName
 */
class Device extends ActiveRecord implements HiddenAttributeInterface, StatusAttributeInterface
{
    use HiddenAttributeTrait;

    public const STATUS_BLOCK = 'block';
    public const STATUS_CONSERVATION = 'not_active'; //0
    public const STATUS_REJECT = 'active'; //1
    public const STATUS_VERIFICATION = 'verification'; //2
    public const STATUS_NO_VERIFICATION = 'not_verification'; //2
    public const STATUS_WRITTEN_OFF = 'written_off'; //3

    public const PERIOD_ZERO = 0;
    public const PERIOD_ONE = 1;
    public const PERIOD_TWO = 2;
    public const PERIOD_TREE = 3;
    public const PERIOD_FOUR = 4;
    public const PERIOD_FIVE = 5;
    public const PERIOD_SIX = 6;
    public const PERIOD_SEVEN = 7;
    public const PERIOD_EIGHT = 8;

    /**
     * @var string|null
     */
    public ?string $linkView = null;

    /**
     * @var string|null
     */
    public ?string $linkBase = null;

    /**
     * @var string|null
     */
    public ?string $certificateNumber = null;

    /**
     * @var string|null
     */
    public ?string $reject = null;

    /**
     * @var string|null
     */
    public ?string $verification = null;

    /**
     * @var string|null
     */
    public ?string $verificationNext = null;

    /**
     * @var string|null|array
     */
    public null|string|array $unitId = null;

    /**
     * @var null|array
     */
    public null|array $colorCell = null;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%device}}';
    }

    /**
     * @return array
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        return ArrayHelper::merge(
            $behaviors,
            [
                'DeviceVirtualAttributesBehavior' => [
                    'class' => DeviceVirtualAttributesBehavior::class
                ]
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['deviceGroupId',], 'required'],
            [['verificationPeriod', 'category', 'yearRelease', 'hidden'], 'integer'],
            [['norma'], 'number'],
            [['description'], 'string'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['stateRegister', 'factoryNumber', 'inventoryNumber',], 'string', 'max' => 255],
            [
                ['deviceGroupId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => DeviceGroup::class,
                'targetAttribute' => ['deviceGroupId' => 'id']
            ],
            [
                ['deviceNameId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => DeviceName::class,
                'targetAttribute' => ['deviceNameId' => 'id']
            ],
            [
                ['devicePropertyId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => DeviceProperty::class,
                'targetAttribute' => ['devicePropertyId' => 'id']
            ],
            [
                ['deviceTypeId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => DeviceType::class,
                'targetAttribute' => ['deviceTypeId' => 'id']
            ],
            [
                ['description', 'standard', 'factoryNumber', 'inventoryNumber'],
                'trim',
                'when' => function ($model, $attribute) {
                    return !empty($model->$attribute);
                }
            ],
            [['devicePropertyId'], 'default', 'value' => null],
            [['status'], 'default', 'value' => self::STATUS_BLOCK],
            ['hidden', 'default', 'value' => HiddenAttributeInterface::HIDDEN_NO],
            [
                'status',
                'in',
                'range' => array_keys(self::getStatusList())
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
            'deviceGroupId' => 'Группа',
            'deviceTypeId' => 'Тип',
            'deviceNameId' => 'Наименование',
            'devicePropertyId' => 'Тех. характ.',
            'stateRegister' => 'Гос. реестр',
            'factoryNumber' => 'Зав. номер',
            'inventoryNumber' => 'Инв. номер',
            'verificationPeriod' => 'Период',
            'norma' => 'Н/Ч',
            'category' => 'Разряд',
            'description' => 'Комментарии',
            'yearRelease' => 'Год выпуска',
            'status' => 'Статус',
            'hidden' => 'Скрыт',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
            /** Виртуальные поля */
            'linkView' => 'Сведения о результатах поверки СИ',
            'linkBase' => 'Сведения о регистрационном номере типа СИ',
            'certificateNumber' => 'Номер свидетельства/ Номер извещения',
            'reject' => 'Забраковка',
            'verification' => 'Поверка',
            'verificationNext' => 'Следующая поверка',
            'unitId' => 'Цех'
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getDeviceConservationsRelation(): ActiveQuery
    {
        return $this->hasMany(DeviceConservation::class, ['deviceId' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getDeviceGroupRelation(): ActiveQuery
    {
        return $this->hasOne(DeviceGroup::class, ['id' => 'deviceGroupId']);
    }

    /**
     * @return ActiveQuery
     */
    public function getDeviceInfoVerificationRelation(): ActiveQuery
    {
        return $this->hasOne(DeviceInfoVerification::class, ['deviceId' => 'id'])
            ->hidden()
            ->andWhere([DeviceInfoVerification::tableName() . '.[[status]]' => StatusAttributeInterface::STATUS_ACTIVE]);
    }

    /**
     * @return ActiveQuery
     */
    public function getDeviceNameRelation(): ActiveQuery
    {
        return $this->hasOne(DeviceName::class, ['id' => 'deviceNameId']);
    }

    /**
     * @return ActiveQuery
     */
    public function getDevicePropertyRelation(): ActiveQuery
    {
        return $this->hasOne(DeviceProperty::class, ['id' => 'devicePropertyId']);
    }

    /**
     * @return ActiveQuery
     */
    public function getDeviceRejectionsRelation(): ActiveQuery
    {
        return $this->hasMany(DeviceRejection::class, ['deviceId' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getDeviceRejectionRelation(): ActiveQuery
    {
        return $this->hasOne(DeviceRejection::class, ['deviceId' => 'id']);
    }


    /**
     * @return ActiveQuery
     */
    public function getDeviceStandardsRelation(): ActiveQuery
    {
        return $this->hasMany(DeviceStandard::class, ['deviceId' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getDeviceStandardRelation(): ActiveQuery
    {
        return $this->hasOne(DeviceStandard::class, ['deviceId' => 'id'])
            ->andWhere([DeviceStandard::tableName() . '.[[hidden]]' => HiddenAttributeInterface::HIDDEN_NO])
            ->andWhere([DeviceStandard::tableName() . '.[[status]]' => StatusAttributeInterface::STATUS_ACTIVE]);
    }

    /**
     * @return ActiveQuery
     */
    public function getDeviceToImpactsRelation(): ActiveQuery
    {
        return $this->hasMany(DeviceToImpact::class, ['deviceId' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getDeviceToImpactRelation(): ActiveQuery
    {
        return $this->hasOne(DeviceToImpact::class, ['deviceId' => 'id'])
            ->andWhere([DeviceToImpact::tableName() . '.[[status]]' => StatusAttributeInterface::STATUS_ACTIVE]);
    }

    /**
     * @return ActiveQuery
     */
    public function getDeviceToUnitsRelation(): ActiveQuery
    {
        return $this->hasMany(DeviceToUnit::class, ['deviceId' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getDeviceToUnitRelation(): ActiveQuery
    {
        return $this->hasOne(DeviceToUnit::class, ['deviceId' => 'id'])
            ->andWhere([DeviceToUnit::tableName() . '.[[status]]' => StatusAttributeInterface::STATUS_ACTIVE]);
    }

    /**
     * @return ActiveQuery
     */
    public function getDeviceTypeRelation(): ActiveQuery
    {
        return $this->hasOne(DeviceType::class, ['id' => 'deviceTypeId']);
    }

    /**
     * @return ActiveQuery
     */
    public function getDeviceVerificationsRelation(): ActiveQuery
    {
        return $this->hasMany(DeviceVerification::class, ['deviceId' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getDeviceVerificationRelation(): ActiveQuery
    {
        return $this->hasOne(DeviceVerification::class, ['deviceId' => 'id'])
            ->andWhere([DeviceVerification::tableName() . '.[[status]]' => StatusAttributeInterface::STATUS_ACTIVE]);
    }

    /**
     * @return DeviceQuery
     */
    public static function find(): DeviceQuery
    {
        return new DeviceQuery(get_called_class());
    }

    /**
     * @return array|string|float|null
     */
    public function getFullName(): array|string|float|null
    {
        return $this->deviceNameRelation->getFullName() .
            ', зав. №' . $this->factoryNumber .
            ', инв. №' . $this->inventoryNumber;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getFullInfo(): string
    {
        $unit = '';
        if (!empty($this->deviceToUnitRelation) && $this->deviceToUnitRelation->unitRelation) {
            $unit = $this->deviceToUnitRelation->unitRelation->getFullName();
        }

        return $this->deviceNameRelation->name . ', ' . $this->deviceTypeRelation->name .
            ' зав. №' . $this->factoryNumber .
            ' инв. №' . $this->inventoryNumber .
            ' (' . $unit . ')' .
            ' раз. ' . $this->category .
            ' н/ч ' . $this->norma;
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

    /**
     * @return string[]
     */
    public static function getStatusList(): array
    {
        return [
            self::STATUS_VERIFICATION => 'Поверен',
            self::STATUS_REJECT => 'Забракован',
            self::STATUS_NO_VERIFICATION => 'Не поверен',
            self::STATUS_WRITTEN_OFF => 'Списан',
            self::STATUS_CONSERVATION => 'Законсервирован',
            self::STATUS_BLOCK => 'Заблокирован',
        ];
    }

    /**
     * @return string|null
     * @throws Exception
     */
    public function getStatus(): ?string
    {
        return ArrayHelper::getValue(self::getStatusList(), $this->status);
    }

    /**
     * @return string[]
     */
    public static function getVerificationPeriodList(): array
    {
        return [
            self::PERIOD_ZERO => '',
            self::PERIOD_ONE => '3 мес.',
            self::PERIOD_TWO => '6 мес',
            self::PERIOD_TREE => '1 год',
            self::PERIOD_FOUR => '2 года',
            self::PERIOD_FIVE => '3 года',
            self::PERIOD_SIX => '4 года',
            self::PERIOD_SEVEN => '5 лет',
            self::PERIOD_EIGHT => '6 лет',
        ];
    }

    /**
     * @return string|null
     * @throws Exception
     */
    public function getVerificationPeriod(): ?string
    {
        return ArrayHelper::getValue(self::getVerificationPeriodList(), $this->verificationPeriod);
    }
}
