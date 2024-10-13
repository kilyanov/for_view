<?php

declare(strict_types=1);

namespace app\modules\rationing\models;

use app\common\database\traits\HiddenAttributeTrait;
use app\common\interface\HiddenAttributeInterface;
use app\modules\personal\modules\special\models\PersonalSpecial;
use app\modules\personal\modules\special\models\query\PersonalSpecialQuery;
use app\modules\rationing\behaviors\RationingDeviceDataBehavior;
use app\modules\rationing\models\query\RationingDeviceDataQuery;
use app\modules\rationing\models\query\RationingDeviceQuery;
use app\modules\unit\models\query\UnitQuery;
use app\modules\unit\models\Unit;
use Exception;
use kilyanov\behaviors\ActiveRecord;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%rationing_device_data}}".
 *
 * @property string $id ID
 * @property int $operationNumber Параграф
 * @property string $rationingDeviceId Прибор
 * @property string $name Операция
 * @property string $unitId Подразделение
 * @property string $specialId Специальность
 * @property string $ed Ед. изм.
 * @property int $countItems Кол-во
 * @property float $periodicity Частота вст.
 * @property int $category Разряд
 * @property float|null $norma Н/Ч на ед.
 * @property float|null $normaAll На операцию
 * @property int $hidden
 * @property int|null $sort Вес
 * @property string $createdAt
 * @property string $updatedAt
 *
 * @property RationingDevice $rationingDeviceRelation
 * @property PersonalSpecial $specialRelation
 * @property Unit $unitRelation
 *
 * @property-read string $fullName
 */
class RationingDeviceData extends ActiveRecord implements HiddenAttributeInterface
{
    use HiddenAttributeTrait;

    /**
     * @return array
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        return ArrayHelper::merge($behaviors, [
            'RationingDeviceDataBehavior' => [
                'class' => RationingDeviceDataBehavior::class,
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%rationing_device_data}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [
                [
                    'operationNumber', 'rationingDeviceId',
                    'name', 'unitId',
                    'specialId', 'ed',
                    'countItems', 'periodicity',
                    'category',
                    ],
                'required'
            ],
            [['operationNumber', 'countItems', 'category', 'hidden', 'sort'], 'integer'],
            [['name'], 'string'],
            [['periodicity', 'norma', 'normaAll'], 'number'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['ed'], 'string', 'max' => 255],
            [
                ['rationingDeviceId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => RationingDevice::class,
                'targetAttribute' => ['rationingDeviceId' => 'id']
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
                ['name', 'ed',],
                'trim',
                'when' => function ($model, $attribute) {
                    return !empty($model->$attribute);
                }
            ],
            ['hidden', 'default', 'value' => HiddenAttributeInterface::HIDDEN_NO],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'operationNumber' => 'Параграф',
            'rationingDeviceId' => 'Прибор',
            'name' => 'Операция',
            'unitId' => 'Подразделение',
            'specialId' => 'Специальность',
            'ed' => 'Ед. изм.',
            'countItems' => 'Кол-во',
            'periodicity' => 'Частота вст.',
            'category' => 'Разряд',
            'norma' => 'Н/Ч на ед.',
            'normaAll' => 'На операцию',
            'hidden' => 'Скрыт',
            'sort' => 'Вес',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[RationingDevice]].
     *
     * @return ActiveQuery|RationingDeviceQuery
     */
    public function getRationingDeviceRelation(): ActiveQuery|RationingDeviceQuery
    {
        return $this->hasOne(RationingDevice::class, ['id' => 'rationingDeviceId']);
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
     * @return RationingDeviceDataQuery the active query used by this AR class.
     */
    public static function find(): RationingDeviceDataQuery
    {
        return new RationingDeviceDataQuery(get_called_class());
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return $this->operationNumber . '. ' . $this->name . ' ' . '(' . $this->normaAll . ')';
    }

    /**
     * @param array $model
     * @return string
     */
    public static function getFullNameMoving(array $model): string
    {
        return $model['operationNumber']. '. ' . $model['name'] . ' ' . '(' .$model['normaAll'] . ')';
    }

    /**
     * @param array $config
     * @return array
     * @throws Exception
     */
    public static function asDropDown(array $config = []): array
    {
        $query = self::find()->hidden();

        return $query->asDropDown();
    }
}
