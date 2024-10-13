<?php

declare(strict_types=1);

namespace app\modules\industry\models;

use app\common\database\traits\HiddenAttributeTrait;
use app\common\interface\HiddenAttributeInterface;
use app\modules\industry\models\query\OrderListQuery;
use app\modules\industry\models\query\OrderToUnitQuery;
use app\modules\unit\models\query\UnitQuery;
use app\modules\unit\models\Unit;
use Exception;
use kilyanov\behaviors\ActiveRecord;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%order_to_unit}}".
 *
 * @property string $id ID
 * @property string|null $orderId Заказ
 * @property string|null $unitId Подразделение
 * @property int $hidden Скрыт
 * @property int|null $sort Вес
 * @property string $createdAt
 * @property string $updatedAt
 *
 * @property OrderList $orderRelation
 * @property Unit $unitRelation
 *
 * @property-read null|string|array|float $fullName
 */
class OrderToUnit extends ActiveRecord implements HiddenAttributeInterface
{
    use HiddenAttributeTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%order_to_unit}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['orderId', 'unitId'], 'required'],
            [['hidden', 'sort'], 'integer'],
            [['createdAt', 'updatedAt'], 'safe'],
            [
                ['unitId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Unit::class,
                'targetAttribute' => ['unitId' => 'id']
            ],
            [
                ['orderId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => OrderList::class,
                'targetAttribute' => ['orderId' => 'id']
            ],
            ['hidden', 'default', 'value' => HiddenAttributeInterface::HIDDEN_NO],
            [
                'unitId',
                'unique',
                'targetAttribute' => ['unitId', 'orderId'],
                'message' => 'Подразделение уже есть в заказе.'
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
            'orderId' => 'Заказ',
            'unitId' => 'Подразделение',
            'hidden' => 'Скрыт',
            'sort' => 'Вес',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
        ];
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
     * @return OrderToUnitQuery the active query used by this AR class.
     */
    public static function find(): OrderToUnitQuery
    {
        return new OrderToUnitQuery(get_called_class());
    }

    /**
     * @return array|string|float|null
     * @throws Exception
     */
    public function getFullName(): array|string|float|null
    {
        return $this->unitRelation->getFullName();
    }

    /**
     * @param array $model
     * @return string
     * @throws Exception
     */
    public static function getFullNameMoving(array $model): string
    {
        $unit = ArrayHelper::getValue(self::getListUnit(), $model['unitId']);

        return $unit === null ? '' : $unit;
    }

    /**
     * @return array
     */
    protected static function getListUnit(): array
    {
        static $list = [];

        if (empty($list)) {
            $list = Unit::find()->hidden()->asDropDown();
        }

        return $list;
    }

    /**
     * @param array $config
     * @return array
     * @throws Exception
     */
    public static function asDropDown(array $config = []): array
    {
        /** @var self $query */
        $query = self::find()->hidden()->orderId(ArrayHelper::getValue($config,'orderId'));

        return $query->asDropDown();
    }
}
