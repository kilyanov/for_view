<?php

declare(strict_types=1);

namespace app\modules\industry\models;

use app\common\database\traits\MonthTrait;
use app\modules\device\models\Device;
use app\modules\device\models\query\DeviceQuery;
use app\modules\impact\models\Impact;
use app\modules\impact\models\query\ImpactQuery;
use app\modules\industry\behaviors\PresentBookBehavior;
use app\modules\industry\models\query\OrderListQuery;
use app\modules\industry\models\query\OrderRationingQuery;
use app\modules\industry\models\query\PresentationBookDataDeviceRepairQuery;
use app\modules\industry\models\query\PresentationBookDataProductQuery;
use app\modules\industry\models\query\PresentationBookQuery;
use app\modules\nso\models\query\StandQuery;
use app\modules\nso\models\Stand;
use app\modules\personal\models\Personal;
use app\modules\personal\models\query\PersonalQuery;
use app\modules\personal\modules\group\models\PersonalGroup;
use app\modules\personal\modules\group\models\query\PersonalGroupQuery;
use app\modules\unit\models\query\UnitQuery;
use app\modules\unit\models\Unit;
use kilyanov\behaviors\ActiveRecord;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%presentation_book}}".
 *
 * @property string $id ID
 * @property string $orderId Заказ
 * @property string $typeOrder Тип заказа
 * @property string $groupId Группа
 * @property string $personalId Специалист
 * @property string $impactId Вид воздействия
 * @property string|null $unitId Подразделение
 * @property string $name Наименование
 * @property string|null $number Зав. номер
 * @property string|null $inventoryNumber Инв. номер
 * @property string|null $deviceVerificationId Прибор (поверка)
 * @property string|null $deviceRepairId Прибор (ремонт)
 * @property string|null $orderRationingId Нормировка
 * @property string|null $standId Стенд
 * @property string $date Дата
 * @property int $year Год
 * @property int $month Месяц
 * @property float|null $norma Н/Ч
 * @property string|null $comment Комментарий
 * @property int|null $status Статус
 * @property string $createdAt
 * @property string|null $updatedAt
 *
 * @property Device $deviceRepairRelation
 * @property Device $deviceVerificationRelation
 * @property Impact $impactRelation
 * @property OrderList $orderRelation
 * @property OrderRationing $orderRationingRelation
 * @property PersonalGroup $groupRelation
 * @property Personal $personalRelation
 * @property PresentationBookDataDeviceRepair[] $presentationBookDataDeviceRepairsRelation
 * @property PresentationBookDataProduct[] $presentationBookDataProductsRelation
 * @property Stand $standRelation
 * @property Unit $unitRelation
 *
 * @property-read null|string|array $urlPresentData
 * @property-read null|string|array|float $fullName
 * @property-read string $template
 */
class PresentationBook extends ActiveRecord
{
    use MonthTrait;

    public const STATUS_NOT_PRESENT = 0;
    public const STATUS_PRESENT = 1;
    public const STATUS_PRESENT_CLOSE_JOB = 2;
    public const STATUS_PRESENT_NOT_CARD = 3;

    /**
     * @var array
     */
    public array $colorCell = [];

    /**
     * @var bool|null
     */
    public ?bool $checkClose = true;

    /**
     * @var string|null
     */
    public ?string $orderRationingDataId = null;

    /**
     * @return array
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        return ArrayHelper::merge(
            $behaviors, [
                'PresentBookBehavior' => [
                    'class' => PresentBookBehavior::class
                ]
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%presentation_book}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['groupId', 'orderId', 'typeOrder', 'personalId', 'impactId', 'date'], 'required'],
            [['date', 'createdAt', 'updatedAt'], 'safe'],
            [['year', 'month', 'status'], 'integer'],
            [['norma'], 'number'],
            [['comment'], 'string'],
            [['typeOrder', 'number', 'inventoryNumber',], 'string', 'max' => 255],
            [['name'], 'string', 'max' => 800],
            [
                ['deviceRepairId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Device::class,
                'targetAttribute' => ['deviceRepairId' => 'id']
            ],
            [
                ['deviceVerificationId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Device::class,
                'targetAttribute' => ['deviceVerificationId' => 'id']
            ],
            [
                ['impactId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Impact::class,
                'targetAttribute' => ['impactId' => 'id']
            ],
            [
                ['orderId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => OrderList::class,
                'targetAttribute' => ['orderId' => 'id']
            ],
            [
                ['orderRationingId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => OrderRationing::class,
                'targetAttribute' => ['orderRationingId' => 'id']
            ],
            [
                ['groupId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => PersonalGroup::class,
                'targetAttribute' => ['groupId' => 'id']
            ],
            [
                ['personalId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Personal::class,
                'targetAttribute' => ['personalId' => 'id']
            ],
            [
                ['standId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Stand::class,
                'targetAttribute' => ['standId' => 'id']
            ],
            [
                ['unitId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Unit::class,
                'targetAttribute' => ['unitId' => 'id']
            ],
            ['deviceVerificationId', 'default', 'value' => null],
            ['deviceRepairId', 'default', 'value' => null],
            ['orderRationingId', 'default', 'value' => null],
            ['standId', 'default', 'value' => null],
            [
                'deviceVerificationId',
                'required',
                'when' => function () {
                    return $this->typeOrder == OrderList::TYPE_DEVICE_VERIFICATION;
                }
            ],
            [
                'deviceRepairId',
                'required',
                'when' => function () {
                    return $this->typeOrder == OrderList::TYPE_DEVICE_REPAIR;
                }
            ],
            [
                'orderRationingId',
                'required',
                'when' => function () {
                    return $this->typeOrder == OrderList::TYPE_PRODUCT;
                }
            ],
            [
                'standId',
                'required',
                'when' => function () {
                    return $this->typeOrder == OrderList::TYPE_STAND;
                }
            ],

            /** Виртуальные поля*/
            [
                ['orderRationingDataId'],
                'string',
            ],
            ['checkClose', 'boolean'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'orderId' => 'Заказ',
            'typeOrder' => 'Тип заказа',
            'personalId' => 'Специалист',
            'impactId' => 'Вид воздействия',
            'groupId' => 'Бригада',
            'unitId' => 'Подразделение',
            'name' => 'Наименование',
            'number' => 'Зав. номер',
            'inventoryNumber' => 'Инв. номер',
            'deviceVerificationId' => 'Прибор (поверка)',
            'deviceRepairId' => 'Прибор (ремонт)',
            'orderRationingId' => 'Нормировка',
            'standId' => 'Стенд',
            'date' => 'Дата',
            'year' => 'Год',
            'month' => 'Месяц',
            'norma' => 'Н/Ч',
            'comment' => 'Комментарий',
            'status' => 'Статус',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
            /** Виртуальные поля */
            'orderRationingDataId' => 'Пункт нормировки',
            'checkClose' => 'Учитывать закрытые часы',
            'salary' => 'Зарплата',
        ];
    }

    /**
     * Gets query for [[DeviceRepair]].
     *
     * @return ActiveQuery|DeviceQuery
     */
    public function getDeviceRepairRelation(): ActiveQuery|DeviceQuery
    {
        return $this->hasOne(Device::class, ['id' => 'deviceRepairId']);
    }

    /**
     * Gets query for [[DeviceVerification]].
     *
     * @return ActiveQuery|DeviceQuery
     */
    public function getDeviceVerificationRelation(): ActiveQuery|DeviceQuery
    {
        return $this->hasOne(Device::class, ['id' => 'deviceVerificationId']);
    }

    /**
     * Gets query for [[Impact]].
     *
     * @return ActiveQuery|ImpactQuery
     */
    public function getImpactRelation(): ActiveQuery|ImpactQuery
    {
        return $this->hasOne(Impact::class, ['id' => 'impactId']);
    }

    /**
     * Gets query for [[Order]].
     *
     * @return ActiveQuery|OrderListQuery
     */
    public function getOrderRelation(): ActiveQuery|OrderListQuery
    {
        return $this->hasOne(OrderList::class, ['id' => 'orderId']);
    }

    /**
     * Gets query for [[OrderRationing]].
     *
     * @return ActiveQuery|OrderRationingQuery
     */
    public function getOrderRationingRelation(): ActiveQuery|OrderRationingQuery
    {
        return $this->hasOne(OrderRationing::class, ['id' => 'orderRationingId']);
    }

    /**
     * Gets query for [[Group]].
     *
     * @return ActiveQuery|PersonalGroupQuery
     */
    public function getGroupRelation(): ActiveQuery|PersonalGroupQuery
    {
        return $this->hasOne(PersonalGroup::class, ['id' => 'groupId']);
    }

    /**
     * Gets query for [[Personal]].
     *
     * @return ActiveQuery|PersonalQuery
     */
    public function getPersonalRelation(): ActiveQuery|PersonalQuery
    {
        return $this->hasOne(Personal::class, ['id' => 'personalId']);
    }

    /**
     * Gets query for [[PresentationBookDataDeviceRepairs]].
     *
     * @return ActiveQuery|PresentationBookDataDeviceRepairQuery
     */
    public function getPresentationBookDataDeviceRepairsRelation(): ActiveQuery|PresentationBookDataDeviceRepairQuery
    {
        return $this->hasMany(PresentationBookDataDeviceRepair::class, ['bookId' => 'id']);
    }

    /**
     * Gets query for [[PresentationBookDataProducts]].
     *
     * @return ActiveQuery|PresentationBookDataProductQuery
     */
    public function getPresentationBookDataProductsRelation(): PresentationBookDataProductQuery|ActiveQuery
    {
        return $this->hasMany(PresentationBookDataProduct::class, ['bookId' => 'id']);
    }

    /**
     * Gets query for [[Stand]].
     *
     * @return ActiveQuery|StandQuery
     */
    public function getStandRelation(): ActiveQuery|StandQuery
    {
        return $this->hasOne(Stand::class, ['id' => 'standId']);
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
     * @return PresentationBookQuery the active query used by this AR class.
     */
    public static function find(): PresentationBookQuery
    {
        return new PresentationBookQuery(get_called_class());
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

    /**
     * @return string[]
     */
    public static function getStatusList(): array
    {
        return [
            self::STATUS_PRESENT_CLOSE_JOB => 'Закрытие работ',
            self::STATUS_PRESENT => 'Предъявлено',
            self::STATUS_NOT_PRESENT => 'Не предъявлено',
            self::STATUS_PRESENT_NOT_CARD => 'Не вкл. в наряд',
        ];
    }

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return self::getStatusList()[$this->status] ?? null;
    }

    /**
     * @return string
     */
    public function getTemplate(): string
    {
        switch ($this->typeOrder) {
            case OrderList::TYPE_PRODUCT:
                return '_form-product';
            case OrderList::TYPE_DEVICE_VERIFICATION:
                return '_form-device-verification';
            case OrderList::TYPE_DEVICE_REPAIR:
                return '_form-device-repair';
            case OrderList::TYPE_STAND:
                return '_form-stand';
            case OrderList::TYPE_STAND_VERIFICATION:
                return '_form-stand-device';
        }
    }

    /**
     * @return array|null
     */
    public function getUrlPresentData(): string|null
    {
        return match ($this->typeOrder) {
            OrderList::TYPE_PRODUCT => '/industry/presentation/data-product/index',
            OrderList::TYPE_DEVICE_REPAIR => '/industry/presentation/data-device-repair/index',
            default => null,
        };
    }
}
