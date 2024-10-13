<?php

declare(strict_types=1);

namespace app\modules\personal\models;

use app\common\database\traits\HiddenAttributeTrait;
use app\common\database\traits\StatusAttributeTrait;
use app\common\interface\HiddenAttributeInterface;
use app\common\interface\StatusAttributeInterface;
use app\common\rbac\CollectionRolls;
use app\modules\personal\interface\TypeAttributeInterface;
use app\modules\personal\models\query\PersonalQuery;
use app\modules\personal\modules\group\models\PersonalGroup;
use app\modules\personal\modules\special\models\PersonalSpecial;
use app\modules\personal\traits\TypeAttributeTrait;
use app\modules\unit\models\Unit;
use Exception;
use kilyanov\behaviors\ActiveRecord;
use Yii;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%personal}}".
 *
 * @property string $id ID
 * @property int $type Тип
 * @property string $specialId Специальность
 * @property string $unitId Подразделение
 * @property string|null $groupId Группа
 * @property string|null $fistName Фамилия
 * @property string|null $lastName Имя
 * @property string|null $secondName Отчество
 * @property int|null $discharge Разряд
 * @property float $salary Зарплата
 * @property float $ratio Коэффициент премии
 * @property string|null $description Примечание
 * @property int $typeSalary Для расчета З/П
 * @property int $hidden
 * @property int $status
 * @property string $createdAt
 * @property string $updatedAt
 *
 * @property PersonalGroup $groupRelation
 * @property PersonalSpecial $specialRelation
 * @property Unit $unitRelation
 *
 *  @property-read null|string|array|float $fullName
 */
class Personal extends ActiveRecord implements
    HiddenAttributeInterface,
    StatusAttributeInterface,
    TypeAttributeInterface
{
    use HiddenAttributeTrait;
    use StatusAttributeTrait;
    use TypeAttributeTrait;

    public const TYPE_SALARY_YES = 1;
    public const TYPE_SALARY_NO = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%personal}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['specialId', 'unitId', 'salary', 'ratio',], 'required'],
            [['discharge', 'typeSalary', 'hidden', 'status', 'type'], 'integer'],
            [['salary', 'ratio'], 'number'],
            [['description'], 'string'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['fistName', 'lastName', 'secondName'], 'string', 'max' => 255],
            [
                ['groupId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => PersonalGroup::class,
                'targetAttribute' => ['groupId' => 'id']
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
            ['hidden', 'default', 'value' => HiddenAttributeInterface::HIDDEN_NO],
            ['status', 'default', 'value' => StatusAttributeInterface::STATUS_ACTIVE],
            ['groupId', 'default', 'value' => null],
            [
                'status',
                'in',
                'range' => [
                    StatusAttributeInterface::STATUS_ACTIVE,
                    StatusAttributeInterface::STATUS_NOT_ACTIVE,
                ]
            ],
            ['type', 'default', 'value' => TypeAttributeInterface::TYPE_JOB],
            [
                'type',
                'in',
                'range' => array_keys(self::getTypeList())
            ],
            [
                ['fistName', 'lastName', 'secondName', 'description'],
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
            'type' => 'Тип',
            'specialId' => 'Специальность',
            'unitId' => 'Подразделение',
            'groupId' => 'Группа',
            'fistName' => 'Фамилия',
            'lastName' => 'Имя',
            'secondName' => 'Отчество',
            'discharge' => 'Разряд',
            'salary' => 'Зарплата',
            'ratio' => 'Коэффициент премии',
            'description' => 'Примечание',
            'typeSalary' => 'Для расчета З/П',
            'hidden' => 'Скрыт',
            'status' => 'Статус',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Group]].
     *
     * @return ActiveQuery
     */
    public function getGroupRelation(): ActiveQuery
    {
        return $this->hasOne(PersonalGroup::class, ['id' => 'groupId']);
    }

    /**
     * Gets query for [[Special]].
     *
     * @return ActiveQuery
     */
    public function getSpecialRelation(): ActiveQuery
    {
        return $this->hasOne(PersonalSpecial::class, ['id' => 'specialId']);
    }

    /**
     * Gets query for [[Unit]].
     *
     * @return ActiveQuery
     */
    public function getUnitRelation(): ActiveQuery
    {
        return $this->hasOne(Unit::class, ['id' => 'unitId']);
    }

    /**
     * {@inheritdoc}
     * @return PersonalQuery the active query used by this AR class.
     */
    public static function find(): PersonalQuery
    {
        return new PersonalQuery(get_called_class());
    }

    /**
     * @return array|string|float|null
     * @throws Exception
     */
    public function getFullName(): array|string|float|null
    {
        $result = [
            !empty($this->fistName) ? $this->fistName : null,
            !empty($this->lastName) ? mb_substr($this->lastName, 0, 1) . '.' : null,
            !empty($this->secondName) ? mb_substr($this->secondName, 0, 1) . '.' : null
        ];
        if (Yii::$app->user->can(CollectionRolls::ROLE_ROOT) && $this->isHidden()) {
            $result[] = '(Скрыто)';
        }

        return implode(' ', $result);
    }

    /**
     * @param array $model
     * @return string
     * @throws Exception
     */
    public static function getFullNameMoving(array $model): string
    {
        $result = [
            ArrayHelper::getValue($model, 'fistName'),
            ArrayHelper::getValue($model, 'lastName') ? mb_substr(ArrayHelper::getValue($model, 'lastName'), 0, 1) . '.' : null,
            ArrayHelper::getValue($model, 'secondName') ? mb_substr(ArrayHelper::getValue($model, 'secondName'), 0, 1) . '.' : null
        ];
        if (Yii::$app->user->can(CollectionRolls::ROLE_ROOT)
            && ArrayHelper::getValue($model, 'hidden') == HiddenAttributeInterface::HIDDEN_YES) {
            $result[] = '(Скрыто)';
        }

        return implode(' ', array_filter($result));
    }

    /**
     * @return string[]
     */
    public static function getTypeSalaryList(): array
    {
        return [
            self::TYPE_SALARY_NO => 'Нет',
            self::TYPE_SALARY_YES => 'Да'
        ];
    }

    /**
     * @return string|null
     * @throws Exception
     */
    public function getTypeSalary(): ?string
    {
        return ArrayHelper::getValue(static::getTypeSalaryList(), $this->typeSalary);
    }

    /**
     * @param array $config
     * @return array
     * @throws Exception
     */
    public static function asDropDown(array $config = []): array
    {
        /** @var PersonalQuery $query */
        $query = self::find()->hidden();
        $unitId = ArrayHelper::getValue($config, 'unitId');
        $status = ArrayHelper::getValue($config, 'status');
        $typeSalary = ArrayHelper::getValue($config, 'typeSalary');
        $query->andFilterWhere(['unitId' => $unitId])
            ->andFilterWhere(['status' => $status])
            ->andFilterWhere(['typeSalary' => $typeSalary]);

        return $query->asDropDown();
    }
}
