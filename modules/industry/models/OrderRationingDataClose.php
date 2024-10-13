<?php

declare(strict_types=1);

namespace app\modules\industry\models;

use app\common\database\traits\MonthTrait;
use app\modules\industry\behaviors\RationingDataCloseBehavior;
use app\modules\industry\models\query\OrderRationingDataCloseQuery;
use app\modules\industry\models\query\OrderRationingDataQuery;
use kilyanov\behaviors\ActiveRecord;
use kilyanov\behaviors\common\IdAttributeBehavior;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%order_rationing_data_close}}".
 *
 * @property string $id ID
 * @property string|null $orderRationingDataId Ссылка на пункт
 * @property float|null $norma Н/Ч
 * @property int $year Год
 * @property int $month Месяц
 *
 * @property OrderRationingData $orderRationingDataRelation
 */
class OrderRationingDataClose extends ActiveRecord
{
    use MonthTrait;

    /**
     * @var string|null
     */
    public ?string $order = null;

    /**
     * @var string|null
     */
    public ?string $machine = null;

    /**
     * @var float|null
     */
    public ?float $sumHour = null;

    /**
     * @var string|null
     */
    public ?string $number_order = null;

    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            'IdAttributeBehavior' => [
                'class' => IdAttributeBehavior::class,
            ],
            'RationingDataCloseBehavior' => [
                'class' => RationingDataCloseBehavior::class,
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%order_rationing_data_close}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['year', 'month'], 'required'],
            [['norma'], 'number'],
            [['year', 'month'], 'integer'],
            [
                ['orderRationingDataId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => OrderRationingData::class,
                'targetAttribute' => ['orderRationingDataId' => 'id']
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
            'orderRationingDataId' => 'Ссылка на пункт',
            'norma' => 'Н/Ч',
            'year' => 'Год',
            'month' => 'Месяц',
            /** виртуальные поля */
            'order' => 'Заказ',
            'sumHour' => 'Н/Ч',
            'number_order' => 'Заказ',
        ];
    }

    /**
     * Gets query for [[OrderRationingData]].
     *
     * @return ActiveQuery|OrderRationingDataQuery
     */
    public function getOrderRationingDataRelation(): OrderRationingDataQuery|ActiveQuery
    {
        return $this->hasOne(OrderRationingData::class, ['id' => 'orderRationingDataId']);
    }

    /**
     * {@inheritdoc}
     * @return OrderRationingDataCloseQuery the active query used by this AR class.
     */
    public static function find(): OrderRationingDataCloseQuery
    {
        return new OrderRationingDataCloseQuery(get_called_class());
    }

    /**
     * @return array|string|float|null
     */
    public function getFullName(): array|string|float|null
    {
        $model = $this->orderRationingDataRelation;
        $result = [$model->point, $model->subItem];
        return implode('.', array_filter($result)) . ' ' . $model->name;
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
        /** @var self $query */
        $query = self::find();

        return $query->asDropDown();
    }
}
