<?php

declare(strict_types=1);

namespace app\modules\unit\models;

use app\common\database\traits\HiddenAttributeTrait;
use app\common\interface\HiddenAttributeInterface;
use app\common\rbac\CollectionRolls;
use app\modules\unit\models\query\UnitQuery;
use Exception;
use kilyanov\behaviors\ActiveRecord;
use Yii;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%unit}}".
 *
 * @property string $id ID
 * @property string|null $parentId Основное подразделение
 * @property string $name Подразделение
 * @property string|null $description Примечание
 * @property int $hidden Скрыто
 * @property int|null $sort
 * @property string $createdAt
 * @property string $updatedAt
 *
 * @property Unit $parentRelation
 * @property Unit[] $unitsRelation
 *
 *  @property-read null|string|array|float $fullName
 */
class Unit extends ActiveRecord implements HiddenAttributeInterface
{
    use HiddenAttributeTrait;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%unit}}';
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['name', ], 'required'],
            [['description'], 'string'],
            [['hidden', 'sort'], 'integer'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['parentId', 'name'], 'string', 'max' => 255],
            [
                ['parentId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Unit::class,
                'targetAttribute' => ['parentId' => 'id']
            ],
            ['hidden', 'default', 'value' => HiddenAttributeInterface::HIDDEN_NO],
            ['parentId', 'default', 'value' => null],
            [
                ['name', 'description'],
                'trim',
                'when' => function($model, $attribute) {
                    return !empty($model->$attribute);
                }
            ],
        ];
    }

    /**
     * @return string[]
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'parentId' => 'Осн. подразделение',
            'name' => 'Подразделение',
            'description' => 'Примечание',
            'hidden' => 'Скрыто',
            'sort' => 'Сортировка',
            'createdAt' => 'Создано',
            'updatedAt' => 'Обновлено',
        ];
    }

    /**
     * @return ActiveQuery|UnitQuery
     */
    public function getParentRelation(): ActiveQuery|UnitQuery
    {
        return $this->hasOne(Unit::class, ['id' => 'parentId']);
    }

    /**
     * @return ActiveQuery|UnitQuery
     */
    public function getUnitsRelation(): ActiveQuery|UnitQuery
    {
        return $this->hasMany(Unit::class, ['parentId' => 'id']);
    }

    /**
     * @return UnitQuery
     */
    public static function find(): UnitQuery
    {
        return new UnitQuery(get_called_class());
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
     * @throws Exception
     */
    public static function getFullNameMoving(array $model): string
    {
        return ArrayHelper::getValue($model, 'name');
    }

    /**
     * @param array $config
     * @return array
     * @throws Exception
     */
    public static function asDropDown(array $config = []): array
    {
        $query = self::find()->hidden();
        if (ArrayHelper::getValue($config, 'showParent') !== null) {
            $query->parent($config['showParent']);
        }
        else {
            $query->parent(true);
        }
        return $query->order()->asDropDown();
    }
}
