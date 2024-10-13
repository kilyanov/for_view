<?php

declare(strict_types=1);

namespace app\modules\impact\models;

use app\common\database\traits\HiddenAttributeTrait;
use app\common\interface\HiddenAttributeInterface;
use app\modules\impact\models\query\ImpactQuery;
use kilyanov\behaviors\ActiveRecord;

/**
 * This is the model class for table "{{%impact}}".
 *
 * @property string $id ID
 * @property string $name Название
 * @property string $mark Обозначение
 * @property string $description Примечание
 * @property int $hidden
 * @property int|null $sort
 * @property string $createdAt
 * @property string $updatedAt
 *
 *  @property-read null|string|array|float $fullName
 */
class Impact extends ActiveRecord implements HiddenAttributeInterface
{
    use HiddenAttributeTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%impact}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'mark',], 'required'],
            [['hidden', 'sort'], 'integer'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['name', 'mark'], 'string', 'max' => 255],
            [['description'], 'string'],
            ['hidden', 'default', 'value' => HiddenAttributeInterface::HIDDEN_NO],
            ['description', 'default', 'value' => null],
            [
                ['name', 'mark', 'description'],
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
            'name' => 'Название',
            'mark' => 'Обозначение',
            'description' => 'Примечание',
            'hidden' => 'Скрыт',
            'sort' => 'Сортировка',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
        ];
    }

    /**
     * {@inheritdoc}
     * @return ImpactQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ImpactQuery(get_called_class());
    }

    /**
     * @return array|string|float|null
     */
    public function getFullName(): array|string|float|null
    {
        return $this->mark;
    }

    /**
     * @param array $model
     * @return string
     */
    public static function getFullNameMoving(array $model): string
    {
        return $model['mark'];
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
