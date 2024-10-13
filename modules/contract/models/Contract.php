<?php

declare(strict_types=1);

namespace app\modules\contract\models;

use app\common\database\traits\HiddenAttributeTrait;
use app\common\interface\HiddenAttributeInterface;
use app\modules\institution\models\Institution;
use app\modules\contract\behaviors\ContractBehavior;
use app\modules\contract\models\query\ContractQuery;
use kilyanov\behaviors\ActiveRecord;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%contract}}".
 *
 * @property string $id ID
 * @property string $institutionId Организация
 * @property string $number Номер
 * @property string $name Название
 * @property string|null $description Описание
 * @property string|null $dateFinding Дата заключения
 * @property string $validityPeriod Срок действия
 * @property int $status Статус
 * @property int $hidden
 * @property string $createdAt
 * @property string $updatedAt
 *
 * @property ContractSpecification[] $contractSpecificationsRelation
 * @property Institution $institutionRelation
 *
 * @property-read null|string|array|float $fullName
 */
class Contract extends ActiveRecord implements HiddenAttributeInterface
{
    use HiddenAttributeTrait;

    public const STATUS_PLANNED = 0;
    public const STATUS_WORK = 1;
    public const STATUS_COMPLETED = 2;
    public const STATUS_THWARTED = 3;

    /**
     * @return array
     */
    public function behaviors(): array
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'ContractBehavior' => [
                    'class' => ContractBehavior::class,
                ],
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%contract}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['institutionId', 'number', 'name', 'validityPeriod',], 'required'],
            [['number', 'name', 'description'], 'string'],
            [['dateFinding', 'validityPeriod', 'createdAt', 'updatedAt'], 'safe'],
            [['status', 'hidden'], 'integer'],
            [
                ['institutionId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Institution::class,
                'targetAttribute' => ['institutionId' => 'id']
            ],
            ['hidden', 'default', 'value' => HiddenAttributeInterface::HIDDEN_NO],
            ['description', 'default', 'value' => null],
            [
                ['number', 'name', 'description'],
                'trim',
                'when' => function($model, $attribute) {
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
            'institutionId' => 'Организация',
            'number' => 'Номер',
            'name' => 'Название',
            'description' => 'Описание',
            'dateFinding' => 'Дата заключения',
            'validityPeriod' => 'Срок действия',
            'status' => 'Статус',
            'hidden' => 'Скрыт',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[ContractSpecifications]].
     *
     * @return ActiveQuery
     */
    public function getContractSpecificationsRelation(): ActiveQuery
    {
        return $this->hasMany(ContractSpecification::class, ['contractId' => 'id']);
    }

    /**
     * Gets query for [[Institution]].
     *
     * @return ActiveQuery
     */
    public function getInstitutionRelation(): ActiveQuery
    {
        return $this->hasOne(Institution::class, ['id' => 'institutionId']);
    }

    /**
     * {@inheritdoc}
     * @return ContractQuery the active query used by this AR class.
     */
    public static function find(): ContractQuery
    {
        return new ContractQuery(get_called_class());
    }

    /**
     * @return array|string|float|null
     */
    public function getFullName(): array|string|float|null
    {
        return $this->number . ' от ' . $this->dateFinding . 'г.';
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
        /** @var ContractQuery $query */
        $query = self::find()->hidden();

        return $query->asDropDown();
    }

    /**
     * @return string[]
     */
    public static function getStatusList(): array
    {
        return [
            self::STATUS_PLANNED => 'Планируется',
            self::STATUS_WORK => 'Действует',
            self::STATUS_COMPLETED => 'Завершён',
            self::STATUS_THWARTED => 'Сорван',
        ];
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return self::getStatusList()[$this->status];
    }
}
