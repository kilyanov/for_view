<?php

declare(strict_types=1);

namespace app\modules\product\models;

use app\common\database\traits\HiddenAttributeTrait;
use app\common\interface\HiddenAttributeInterface;
use app\modules\product\models\query\ProductBlockQuery;
use app\modules\product\models\query\ProductNodeQuery;
use app\modules\product\models\query\ProductQuery;
use kilyanov\behaviors\ActiveRecord;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%product}}".
 *
 * @property string $id ID
 * @property string $name Название
 * @property string $mark Обозначение
 * @property string|null $description Примечание
 * @property int $hidden
 * @property int|null $sort
 * @property string $createdAt
 * @property string $updatedAt
 *
 * @property ProductBlock[] $productBlocksRelation
 * @property ProductNode[] $productNodesRelation
 *
 * @property-read null|string|array|float $fullName
 */
class Product extends ActiveRecord implements HiddenAttributeInterface
{
    use HiddenAttributeTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%product}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['mark',], 'required'],
            [['description'], 'string'],
            [['hidden', 'sort'], 'integer'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['name', 'mark'], 'string', 'max' => 255],
            ['hidden', 'default', 'value' => HiddenAttributeInterface::HIDDEN_NO],
            [['description', 'name'], 'default', 'value' => null],
            [
                ['name', 'mark', 'description'],
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
            'mark' => 'Обозначение',
            'description' => 'Примечание',
            'hidden' => 'Скрыто',
            'sort' => 'Sort',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[ProductBlocks]].
     *
     * @return ActiveQuery|ProductBlockQuery
     */
    public function getProductBlocksRelation(): ActiveQuery|ProductBlockQuery
    {
        return $this->hasMany(ProductBlock::class, ['productId' => 'id']);
    }

    /**
     * Gets query for [[ProductNodes]].
     *
     * @return ActiveQuery|ProductNodeQuery
     */
    public function getProductNodesRelation(): ActiveQuery|ProductNodeQuery
    {
        return $this->hasMany(ProductNode::class, ['productId' => 'id']);
    }

    /**
     * @return ProductQuery
     */
    public static function find(): ProductQuery
    {
        return new ProductQuery(get_called_class());
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
