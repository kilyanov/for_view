<?php

declare(strict_types=1);

namespace app\modules\personal\modules\special\models;

use app\common\database\traits\HiddenAttributeTrait;
use app\common\interface\HiddenAttributeInterface;
use app\common\rbac\CollectionRolls;
use app\modules\personal\models\Personal;
use app\modules\personal\modules\special\models\query\PersonalSpecialQuery;
use Exception;
use kilyanov\behaviors\ActiveRecord;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%personal_special}}".
 *
 * @property string $id ID
 * @property string $name Название
 * @property string|null $description Примечание
 * @property int $hidden
 * @property int|null $sort
 * @property string $createdAt
 * @property string $updatedAt
 *
 * @property Personal[] $personalsRelation
 *
 * @property-read null|string|array|float $fullName
 */
class PersonalSpecial extends ActiveRecord implements HiddenAttributeInterface
{
    use HiddenAttributeTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%personal_special}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name',], 'required'],
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
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
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
        return $this->hasMany(Personal::class, ['specialId' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return PersonalSpecialQuery the active query used by this AR class.
     */
    public static function find(): PersonalSpecialQuery
    {
        return new PersonalSpecialQuery(get_called_class());
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
     */
    public static function asDropDown(array $config = []): array
    {
        $query = self::find()->hidden();
        /** @var $query PersonalSpecialQuery */
        return $query->asDropDown();
    }
}
