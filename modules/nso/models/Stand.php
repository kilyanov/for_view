<?php

declare(strict_types=1);

namespace app\modules\nso\models;

use app\common\database\traits\HiddenAttributeTrait;
use app\common\interface\HiddenAttributeInterface;
use app\modules\nso\behaviors\StandBehavior;
use app\modules\nso\models\query\StandChartQuery;
use app\modules\nso\models\query\StandDateServiceQuery;
use app\modules\nso\models\query\StandQuery;
use app\modules\unit\models\query\UnitQuery;
use app\modules\unit\models\Unit;
use kilyanov\behaviors\ActiveRecord;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%stand}}".
 *
 * @property string $id ID
 * @property string $unitId Подразделение
 * @property string|null $number Номер стенда
 * @property string|null $name Название стенда
 * @property string|null $mark Чертежный номер
 * @property string|null $inventoryNumber Инвентарный номер
 * @property float|null $standardHours Н/ч
 * @property int|null $category Категория
 * @property int|null $conservation Консервация
 * @property string $dateVerifications Дата последнего обслуживания
 * @property string|null $description Примечание
 * @property int $hidden
 * @property string $createdAt
 * @property string $updatedAt
 *
 * @property StandChart[] $standChartsRelation
 * @property StandDateService[] $standDateServicesRelation
 * @property Unit $unitRelation
 *
 * @property-read null|string|array|float $fullName
 */
class Stand extends ActiveRecord implements HiddenAttributeInterface
{
    use HiddenAttributeTrait;

    /**
     * @return array
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        return ArrayHelper::merge(
            $behaviors, [
                'StandBehavior' => [
                    'class' => StandBehavior::class
                ]
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%stand}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['unitId', 'dateVerifications',], 'required'],
            [['name', 'description'], 'string'],
            [['standardHours'], 'number'],
            [['category', 'conservation', 'hidden'], 'integer'],
            [['dateVerifications', 'createdAt', 'updatedAt'], 'safe'],
            [['number', 'mark', 'inventoryNumber'], 'string', 'max' => 255],
            [
                ['unitId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Unit::class,
                'targetAttribute' => ['unitId' => 'id']
            ],
            ['hidden', 'default', 'value' => HiddenAttributeInterface::HIDDEN_NO],
            [
                ['name', 'description', 'number', 'mark', 'inventoryNumber'],
                'trim',
                'when' => function ($model, $attribute) {
                    return !empty($model->$attribute);
                }
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
            'unitId' => 'Подразделение',
            'number' => 'Номер стенда',
            'name' => 'Название стенда',
            'mark' => 'Чертежный номер',
            'inventoryNumber' => 'Инвентарный номер',
            'standardHours' => 'Н/ч',
            'category' => 'Категория',
            'conservation' => 'Консервация',
            'dateVerifications' => 'Дата последнего обслуживания',
            'description' => 'Примечание',
            'hidden' => 'Скрыт',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[StandCharts]].
     *
     * @return ActiveQuery|StandChartQuery
     */
    public function getStandChartsRelation(): StandChartQuery|ActiveQuery
    {
        return $this->hasMany(StandChart::class, ['standId' => 'id']);
    }

    /**
     * Gets query for [[StandDateServices]].
     *
     * @return ActiveQuery|StandDateServiceQuery
     */
    public function getStandDateServicesRelation(): ActiveQuery|StandDateServiceQuery
    {
        return $this->hasMany(StandDateService::class, ['standId' => 'id']);
    }

    /**
     * Gets query for [[Unit]]
     *
     * @return ActiveQuery|UnitQuery
     */
    public function getUnitRelation(): ActiveQuery|UnitQuery
    {
        return $this->hasOne(Unit::class, ['id' => 'unitId']);
    }

    /**
     * {@inheritdoc}
     * @return StandQuery the active query used by this AR class.
     */
    public static function find(): StandQuery
    {
        return new StandQuery(get_called_class());
    }

    /**
     * @return array|string|float|null
     */
    public function getFullName(): array|string|float|null
    {
        if ($this->name === null) {
            return $this->description;
        } else {
            $name = implode(' ', array_filter([$this->name, $this->mark]));
            $number = empty($this->number) ? null : 'Стенд № ' . $this->number;
            $inventoryNumber = empty($this->inventoryNumber) ? null : 'инв. ' . $this->inventoryNumber;
            $product = empty($this->description) ? null : '(' . $this->description . ')';

            return implode(', ', array_filter([$number, $name, $inventoryNumber, $product]));
        }
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
        $query = self::find()->hidden();

        return $query->asDropDown();
    }
}
