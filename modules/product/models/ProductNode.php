<?php

declare(strict_types=1);

namespace app\modules\product\models;

use app\common\database\traits\HiddenAttributeTrait;
use app\common\interface\HiddenAttributeInterface;
use app\modules\product\models\query\ProductBlockQuery;
use app\modules\product\models\query\ProductNodeQuery;
use app\modules\product\models\query\ProductQuery;
use Exception;
use kilyanov\behaviors\ActiveRecord;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%product_node}}".
 *
 * @property string $id ID
 * @property string $productId Изделие
 * @property string $name Название
 * @property string $mark Обозначение
 * @property string|null $description Примечание
 * @property int $hidden
 * @property int|null $sort
 * @property string $createdAt
 * @property string $updatedAt
 *
 * @property Product $productRelation
 * @property ProductBlock[] $productBlocksRelation
 *
 * @property-read null|string|array|float $fullName
 */
class ProductNode extends ActiveRecord implements HiddenAttributeInterface
{
    use HiddenAttributeTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%product_node}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['productId', 'name',], 'required'],
            [['description'], 'string'],
            [['hidden', 'sort'], 'integer'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['name', 'mark'], 'string', 'max' => 255],
            [
                ['productId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Product::class,
                'targetAttribute' => ['productId' => 'id']
            ],
            ['hidden', 'default', 'value' => HiddenAttributeInterface::HIDDEN_NO],
            [['description', 'mark'], 'default', 'value' => null],
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
            'productId' => 'Изделие',
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
     * Gets query for [[Product]].
     *
     * @return ActiveQuery|ProductQuery
     */
    public function getProductRelation(): ActiveQuery|ProductQuery
    {
        return $this->hasOne(Product::class, ['id' => 'productId']);
    }

    /**
     * Gets query for [[ProductBlocks]].
     *
     * @return ActiveQuery|ProductBlockQuery
     */
    public function getProductBlocksRelation(): ActiveQuery|ProductBlockQuery
    {
        return $this->hasMany(ProductBlock::class, ['productNodeId' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return ProductNodeQuery the active query used by this AR class.
     */
    public static function find(): ProductNodeQuery
    {
        return new ProductNodeQuery(get_called_class());
    }

    /**
     * @return array|string|float|null
     */
    public function getFullName(): array|string|float|null
    {
        $product = !empty($this->productRelation->getFullName()) ?
            'изд. ' . $this->productRelation->getFullName() : null;

        return implode(' ', array_filter([
            $this->name, $this->mark, $product
        ]));
    }

    /**
     * @param array $model
     * @return string
     */
    public static function getFullNameMoving(array $model): string
    {
        return implode(' ', array_filter([
            $model['name'], $model['mark']
        ]));
    }

    /**
     * @param array $config
     * @return array
     * @throws Exception
     */
    public static function asDropDown(array $config = []): array
    {
        /** @var self $query */
        $query = self::find()->hidden();
        $query->andFilterWhere([self::tableName() . '.[[productId]]' => ArrayHelper::getValue($config, 'productId')]);

        return $query->asDropDown();
    }
}
