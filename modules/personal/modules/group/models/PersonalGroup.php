<?php

declare(strict_types=1);

namespace app\modules\personal\modules\group\models;

use app\common\database\traits\HiddenAttributeTrait;
use app\common\interface\HiddenAttributeInterface;
use app\common\rbac\CollectionRolls;
use app\modules\personal\models\Personal;
use app\modules\personal\modules\group\models\query\PersonalGroupQuery;
use app\modules\unit\models\Unit;
use Exception;
use kilyanov\behaviors\ActiveRecord;
use Yii;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%personal_group}}".
 *
 * @property string $id ID
 * @property string $unitId Подразделение
 * @property string $name Название
 * @property string|null $description Примечание
 * @property int $hidden
 * @property int|null $sort
 * @property string $createdAt
 * @property string $updatedAt
 *
 * @property Personal[] $personalsRelation
 * @property Unit $unitRelation
 *
 * @property-read null|string|array|float $fullName
 */
class PersonalGroup extends ActiveRecord implements HiddenAttributeInterface
{
    use HiddenAttributeTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%personal_group}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'unitId'], 'required'],
            [['description'], 'string'],
            [['hidden', 'sort'], 'integer'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['name'], 'string', 'max' => 255],
            ['hidden', 'default', 'value' => HiddenAttributeInterface::HIDDEN_NO],
            [
                ['name', 'description'],
                'trim',
                'when' => function ($model, $attribute) {
                    return !empty($model->$attribute);
                }
            ],
            [
                ['unitId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Unit::class,
                'targetAttribute' => ['unitId' => 'id']
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
            'name' => 'Название',
            'description' => 'Примечание',
            'hidden' => 'Скрыт',
            'sort' => 'Сортировка',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Personals]].
     *
     * @return ActiveQuery
     */
    public function getPersonalsRelation(): ActiveQuery
    {
        return $this->hasMany(Personal::class, ['groupId' => 'id']);
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
     * @return PersonalGroupQuery the active query used by this AR class.
     */
    public static function find(): PersonalGroupQuery
    {
        return new PersonalGroupQuery(get_called_class());
    }

    /**
     * @return array|string|float|null
     * @throws Exception
     */
    public function getFullName(): array|string|float|null
    {
        $hiddenValue = $this->isHidden() ? ' (Скрыто)' : '';
        return (Yii::$app->user->can(CollectionRolls::ROLE_ROOT)) ?
            $this->name . $hiddenValue : $this->name;
    }

    /**
     * @param array $model
     * @return string
     */
    public static function getFullNameMoving(array $model): string
    {
        return $model['name'];
    }

    /**
     * @param array $config
     * @return array
     * @throws Exception
     */
    public static function asDropDown(array $config = []): array
    {
        $query = self::find()->hidden();
        if ($unitId = ArrayHelper::getValue($config, 'unitId')) {
            $query->andFilterWhere(['unitId' => $unitId]);
        } else {
            return [];
        }

        return $query->asDropDown();
    }
}
