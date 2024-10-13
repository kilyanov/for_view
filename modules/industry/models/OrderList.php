<?php

declare(strict_types=1);

namespace app\modules\industry\models;

use app\common\database\traits\HiddenAttributeTrait;
use app\common\interface\HiddenAttributeInterface;
use app\modules\contract\models\Contract;
use app\modules\contract\models\query\ContractQuery;
use app\modules\industry\models\query\OrderListQuery;
use app\modules\industry\models\query\OrderToImpactQuery;
use app\modules\industry\models\query\OrderToProductQuery;
use app\modules\industry\models\query\OrderToUnitQuery;
use Exception;
use kilyanov\behaviors\ActiveRecord;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%order_list}}".
 *
 * @property string $id ID
 * @property string $type Тип
 * @property string $contractId Контракт
 * @property string $numberScore Счет
 * @property string $number Номер
 * @property int $year Год
 * @property int $status Статус
 * @property string|null $description Комментарии
 * @property int $hidden Скрыт
 * @property int|null $sort Вес
 * @property string $createdAt
 * @property string $updatedAt
 *
 * @property Contract $contractRelation
 * @property OrderToImpact[] $orderToImpactsRelation
 * @property OrderToProduct[] $orderToProductsRelation
 * @property OrderToUnit[] $orderToUnitsRelation
 *
 * @property-read array|string[] $colorCell
 * @property-read string $fullName
 */
class OrderList extends ActiveRecord implements HiddenAttributeInterface
{
    use HiddenAttributeTrait;

    public const TYPE_PRODUCT = 'vvt';//ВВТ
    public const TYPE_DEVICE_VERIFICATION = 'device_verification';//поверка приборов - заказ 230801
    public const TYPE_DEVICE_REPAIR = 'device_repair';//Ремонт приборов - заказ 230801
    public const TYPE_STAND = 'stand';//Стенды - заказ 230801
    public const TYPE_STAND_VERIFICATION = 'stand_device';//Стенды обслуживание УУТЭ - заказ 230801

    public const STATUS_CLOSE = 0;
    public const STATUS_OPEN = 1;
    public const STATUS_PLAN = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%order_list}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['type', 'contractId', 'numberScore', 'number', 'year', 'status',], 'required'],
            [['year', 'status', 'hidden', 'sort'], 'integer'],
            [['description'], 'string'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['numberScore', 'number'], 'string', 'max' => 255],
            [
                ['contractId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Contract::class,
                'targetAttribute' => ['contractId' => 'id']
            ],
            [
                ['number', 'description'],
                'trim',
                'when' => function ($model, $attribute) {
                    return !empty($model->$attribute);
                }
            ],
            ['hidden', 'default', 'value' => HiddenAttributeInterface::HIDDEN_NO],
            ['sort', 'default', 'value' => null],
            [
                'status',
                'in',
                'range' => [
                    self::STATUS_OPEN,
                    self::STATUS_CLOSE,
                    self::STATUS_PLAN
                ]
            ],
            [
                'type',
                'in',
                'range' => [
                    self::TYPE_DEVICE_REPAIR,
                    self::TYPE_STAND,
                    self::TYPE_DEVICE_VERIFICATION,
                    self::TYPE_PRODUCT,
                    self::TYPE_STAND_VERIFICATION
                ]
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
            'type' => 'Тип',
            'contractId' => 'Контракт',
            'numberScore' => 'Счет',
            'number' => 'Номер',
            'year' => 'Год',
            'status' => 'Статус',
            'description' => 'Комментарии',
            'hidden' => 'Скрыт',
            'sort' => 'Вес',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Contract]].
     *
     * @return ActiveQuery|ContractQuery
     */
    public function getContractRelation(): ActiveQuery|ContractQuery
    {
        return $this->hasOne(Contract::class, ['id' => 'contractId']);
    }

    /**
     * Gets query for [[OrderToImpacts]].
     *
     * @return ActiveQuery|OrderToImpactQuery
     */
    public function getOrderToImpactsRelation(): ActiveQuery|OrderToImpactQuery
    {
        return $this->hasMany(OrderToImpact::class, ['orderId' => 'id']);
    }

    /**
     * Gets query for [[OrderToProducts]].
     *
     * @return ActiveQuery|OrderToProductQuery
     */
    public function getOrderToProductsRelation(): ActiveQuery|OrderToProductQuery
    {
        return $this->hasMany(OrderToProduct::class, ['orderId' => 'id']);
    }

    /**
     * Gets query for [[OrderToUnits]].
     *
     * @return ActiveQuery|OrderToUnitQuery
     */
    public function getOrderToUnitsRelation(): OrderToUnitQuery|ActiveQuery
    {
        return $this->hasMany(OrderToUnit::class, ['orderId' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return OrderListQuery the active query used by this AR class.
     */
    public static function find(): OrderListQuery
    {
        return new OrderListQuery(get_called_class());
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return $this->number;
    }

    /**
     * @param array $model
     * @return string
     */
    public static function getFullNameMoving(array $model): string
    {
        return $model['number'];
    }

    /**
     * @param array $config
     * @return array
     * @throws Exception
     */
    public static function asDropDown(array $config = []): array
    {
        /** @var OrderListQuery $query */
        $query = self::find()
            ->hidden()
            ->status(ArrayHelper::getValue($config, 'status'));

        return $query->asDropDown();
    }

    /**
     * @return string[]
     */
    public static function getTypeList(): array
    {
        return [
            self::TYPE_PRODUCT => 'ВВТ',
            self::TYPE_DEVICE_VERIFICATION => 'Поверка СИ',
            self::TYPE_DEVICE_REPAIR => 'Ремонт СИ',
            self::TYPE_STAND => 'Стенды',
            self::TYPE_STAND_VERIFICATION => 'УУТЭ',
        ];
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return self::getTypeList()[$this->type] ?? null;
    }

    /**
     * @return string[]
     */
    public static function getStatusList(): array
    {
        return [
            self::STATUS_OPEN => 'Открыт',
            self::STATUS_CLOSE => 'Закрыт',
            self::STATUS_PLAN => 'Планируется',
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
     * @return array
     */
    public function getColorCell(): array
    {
        if ($this->status == self::STATUS_CLOSE) {
            return ['class' => "table-warning"];
        }

        return [];
    }
}
