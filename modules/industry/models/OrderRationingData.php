<?php

declare(strict_types=1);

namespace app\modules\industry\models;

use app\common\database\traits\HiddenAttributeTrait;
use app\common\interface\HiddenAttributeInterface;
use app\modules\industry\behaviors\OrderRationingDataBehavior;
use app\modules\industry\behaviors\RationingBehavior;
use app\modules\industry\models\query\MachineQuery;
use app\modules\industry\models\query\OrderRationingDataCloseQuery;
use app\modules\industry\models\query\OrderRationingDataQuery;
use app\modules\industry\models\query\OrderRationingQuery;
use app\modules\personal\modules\special\models\PersonalSpecial;
use app\modules\personal\modules\special\models\query\PersonalSpecialQuery;
use app\modules\unit\models\query\UnitQuery;
use app\modules\unit\models\Unit;
use Exception;
use kilyanov\behaviors\ActiveRecord;
use yii\bootstrap5\Html;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%order_rationing_data}}".
 *
 * @property string $id ID
 * @property string $rationingId Нормировка
 * @property int $type Тип пункта
 * @property int|null $point Пункт
 * @property int|null $subItem Параграф
 * @property string $name Операция
 * @property string|null $machineId МК
 * @property string|null $unitId Подразделение
 * @property string|null $ed Ед. изм.
 * @property int|null $countItems Кол-во
 * @property float|null $periodicity Частота вст.
 * @property int|null $category Разряд
 * @property float|null $norma Н/Ч на ед.
 * @property float|null $normaAll Н/Ч
 * @property string|null $specialId Специальность
 * @property string|null $comment Коментарии
 * @property int|null $sort Вес
 * @property int|null $checkList Прикрепленный список
 * @property int $hidden
 * @property string $createdAt
 * @property string|null $updatedAt
 *
 * @property Machine $machineRelation
 * @property OrderRationingDataClose[] $orderRationingDataClosesRelation
 * @property OrderRationing $rationingRelation
 * @property PersonalSpecial $specialRelation
 * @property Unit $unitRelation
 *
 * @property float|null $closeNorma Закрыто Н/Ч
 * @property float|null $stayNorma Осталось Н/Ч
 * @property float|null $colorCell Цвет строки
 *
 * @property-read null|string|array|float $fullName
 * @property-read null|string|array|float $number
 */
class OrderRationingData extends ActiveRecord implements HiddenAttributeInterface
{
    use HiddenAttributeTrait;

    public const TYPE_MK = 1;//mk
    public const TYPE_POINT = 2;//point
    public const TYPE_SUB_POINT = 3;//subItem

    /**
     * @var float
     */
    public float $closeNorma = 0.00;

    /**
     * @var float
     */
    public float $stayNorma = 0.00;

    /**
     * @var array
     */
    public array $colorCell = [];

    /**
     * @return array
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        return ArrayHelper::merge($behaviors, [
            'RationingBehavior' => [
                'class' => RationingBehavior::class,
            ],
            'OrderRationingDataBehavior' => [
                'class' => OrderRationingDataBehavior::class,
            ]
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%order_rationing_data}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['rationingId', 'type', 'name',], 'required'],
            [['type', 'point', 'subItem', 'countItems', 'category', 'sort', 'checkList', 'hidden'], 'integer'],
            [['name', 'comment'], 'string'],
            [['periodicity', 'norma', 'normaAll'], 'number'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['ed'], 'string', 'max' => 255],
            [
                ['machineId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Machine::class,
                'targetAttribute' => ['machineId' => 'id']
            ],
            [
                ['rationingId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => OrderRationing::class,
                'targetAttribute' => ['rationingId' => 'id']
            ],
            [
                ['specialId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => PersonalSpecial::class,
                'targetAttribute' => ['specialId' => 'id']
            ],
            [
                ['unitId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Unit::class,
                'targetAttribute' => ['unitId' => 'id']
            ],
            [
                ['name', 'comment', 'ed'],
                'trim',
                'when' => function ($model, $attribute) {
                    return !empty($model->$attribute);
                }
            ],
            ['hidden', 'default', 'value' => HiddenAttributeInterface::HIDDEN_NO],
            ['unitId', 'default', 'value' => null],
            ['specialId', 'default', 'value' => null],
            ['machineId', 'default', 'value' => null],
            ['type', 'in', 'range' => array_keys(self::getTypeList())],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'rationingId' => 'Нормировка',
            'type' => 'Тип пункта',
            'point' => 'Пункт',
            'subItem' => 'Параграф',
            'name' => 'Операция',
            'machineId' => 'МК',
            'unitId' => 'Подразделение',
            'ed' => 'Ед. изм.',
            'countItems' => 'Кол-во',
            'periodicity' => 'Частота вст.',
            'category' => 'Разряд',
            'norma' => 'Н/Ч на ед.',
            'normaAll' => 'Н/Ч',
            'specialId' => 'Специальность',
            'comment' => 'Коментарии',
            'sort' => 'Вес',
            'checkList' => 'Прикрепленный список',
            'hidden' => 'Скрыт',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
            /** виртуальные поля */
            'closeNorma' => 'Закрыто',
            'stayNorma' => 'Осталось'
        ];
    }

    /**
     * Gets query for [[Machine]].
     *
     * @return ActiveQuery|MachineQuery
     */
    public function getMachineRelation(): MachineQuery|ActiveQuery
    {
        return $this->hasOne(Machine::class, ['id' => 'machineId']);
    }

    /**
     * Gets query for [[OrderRationingDataCloses]].
     *
     * @return ActiveQuery|OrderRationingDataCloseQuery
     */
    public function getOrderRationingDataClosesRelation(): ActiveQuery|OrderRationingDataCloseQuery
    {
        return $this->hasMany(OrderRationingDataClose::class, ['orderRationingDataId' => 'id']);
    }

    /**
     * Gets query for [[Rationing]].
     *
     * @return ActiveQuery|OrderRationingQuery
     */
    public function getRationingRelation(): ActiveQuery|OrderRationingQuery
    {
        return $this->hasOne(OrderRationing::class, ['id' => 'rationingId']);
    }

    /**
     * Gets query for [[Special]].
     *
     * @return ActiveQuery|PersonalSpecialQuery
     */
    public function getSpecialRelation(): ActiveQuery|PersonalSpecialQuery
    {
        return $this->hasOne(PersonalSpecial::class, ['id' => 'specialId']);
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
     * @return OrderRationingDataQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new OrderRationingDataQuery(get_called_class());
    }

    /**
     * @return array|string|float|null
     */
    public function getFullName(): array|string|float|null
    {
        switch ($this->type) {
            case self::TYPE_MK:
                $machine = self::getMachineList()[$this->machineId];
                /** @var $machine Machine */
                $items[] = Html::tag('strong', 'МК №' . $machine->number . ' -');
                break;
            case self::TYPE_POINT:
                $items[] = Html::tag('strong', $this->point . '.');
                break;
            case self::TYPE_SUB_POINT:
                $items[] = $this->point . '.' . $this->subItem;
                break;
        }
        $items[] = $this->type == self::TYPE_POINT ? Html::tag('strong', $this->name) : $this->name;
        $items[] = '- ' . $this->sort;

        return implode(' ', $items);
    }

    /**
     * @param array $model
     * @return string
     * @throws Exception
     */
    public static function getFullNameMoving(array $model): string
    {
        $items = [];
        switch ($model['type']) {
            case self::TYPE_MK:
                $machine = ArrayHelper::getValue(self::getMachineList(), $model['machineId'], '');
                /** @var $machine string */
                $items[] = Html::tag('strong', 'МК №' . $machine . ' -');
                break;
            case self::TYPE_POINT:
                $items[] = Html::tag('strong', $model['point'] . '.');
                break;
            case self::TYPE_SUB_POINT:
                $items[] = $model['point'] . '.' . $model['subItem'];
                break;
        }
        $items[] = $model['type'] === 'point' ? Html::tag('strong', $model['name']) : $model['name'];
        $items[] = '- ' . $model['sort'];

        return implode(' ', $items);
    }

    /**
     * @param array $config
     * @return array
     */
    public static function asDropDown(array $config = []): array
    {
        $query = self::find()->hidden();

        return $query->asDropDown();
    }

    /**
     * @return array
     */
    public static function getTypeList(): array
    {
        return [
            self::TYPE_MK => 'МК',
            self::TYPE_POINT => 'Пункт',
            self::TYPE_SUB_POINT => 'Подпункт'
        ];
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::getTypeList()[$this->type];
    }

    /**
     * @return array
     */
    protected static function getMachineList(): array
    {
        static $machines = [];
        if (empty($machines)) {
            $machines = Machine::find()->hidden()->asDropDown();
        }

        return $machines;
    }

    /**
     * @return array|string|float|null
     */
    public function getNumber(): array|string|float|null
    {
        $items = [];
        switch ($this->type) {
            case self::TYPE_MK:
                $machine = self::getMachineList()[$this->machineId];
                /** @var $machine Machine */
                $items[] = Html::tag('strong', 'МК №' . $machine->number . ' -');
                break;
            case self::TYPE_POINT:
                $items[] = Html::tag('strong', $this->point . '.');
                break;
            case self::TYPE_SUB_POINT:
                $items[] = $this->point . '.' . $this->subItem;
                break;
        }

        return implode(' ', $items);
    }
}
