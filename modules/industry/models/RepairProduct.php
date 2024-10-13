<?php

declare(strict_types=1);

namespace app\modules\industry\models;

use app\common\database\traits\HiddenAttributeTrait;
use app\common\interface\HiddenAttributeInterface;
use app\modules\industry\models\query\RepairProductQuery;
use app\modules\product\models\Product;
use app\modules\product\models\ProductBlock;
use app\modules\product\models\ProductNode;
use app\modules\product\models\query\ProductBlockQuery;
use app\modules\product\models\query\ProductNodeQuery;
use app\modules\product\models\query\ProductQuery;
use kilyanov\behaviors\ActiveRecord;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%repair_product}}".
 *
 * @property string $id ID
 * @property string $productId Изделие
 * @property string|null $productNodeId Система
 * @property string|null $productBlockId Блок
 * @property string $number Зав. №
 * @property string|null $comment Примечание
 * @property int $hidden
 * @property string $createdAt
 * @property string $updatedAt
 *
 * @property Product $productRelation
 * @property ProductBlock $productBlockRelation
 * @property ProductNode $productNodeRelation
 *
 * @property-read null|string|array|float $fullName
 */
class RepairProduct extends ActiveRecord implements HiddenAttributeInterface
{
    use HiddenAttributeTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%repair_product}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['productId', 'number'], 'required'],
            [['comment'], 'string'],
            [['hidden'], 'integer'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['number'], 'string', 'max' => 255],
            [
                ['productBlockId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => ProductBlock::class,
                'targetAttribute' => ['productBlockId' => 'id']
            ],
            [
                ['productId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Product::class,
                'targetAttribute' => ['productId' => 'id']
            ],
            [
                ['productNodeId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => ProductNode::class,
                'targetAttribute' => ['productNodeId' => 'id']
            ],
            ['hidden', 'default', 'value' => HiddenAttributeInterface::HIDDEN_NO],
            ['productNodeId', 'default', 'value' => null],
            ['productBlockId', 'default', 'value' => null],
            ['comment', 'default', 'value' => null],
            [
                ['number', 'comment'],
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
            'productId' => 'Изделие',
            'productNodeId' => 'Система',
            'productBlockId' => 'Блок',
            'number' => 'Зав. №',
            'comment' => 'Примечание',
            'hidden' => 'Скрыт',
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
     * Gets query for [[ProductBlock]].
     *
     * @return ActiveQuery|ProductBlockQuery
     */
    public function getProductBlockRelation(): ActiveQuery|ProductBlockQuery
    {
        return $this->hasOne(ProductBlock::class, ['id' => 'productBlockId']);
    }

    /**
     * Gets query for [[ProductNode]].
     *
     * @return ActiveQuery|ProductNodeQuery
     */
    public function getProductNodeRelation(): ActiveQuery|ProductNodeQuery
    {
        return $this->hasOne(ProductNode::class, ['id' => 'productNodeId']);
    }

    /**
     * {@inheritdoc}
     * @return RepairProductQuery the active query used by this AR class.
     */
    public static function find(): RepairProductQuery
    {
        return (new RepairProductQuery(get_called_class()))->with([
            'productBlockRelation', 'productNodeRelation', 'productRelation'
        ]);
    }

    /**
     * @return array|string|float|null
     */
    public function getFullName(): array|string|float|null
    {
        if ($this->productBlockRelation) {
            return $this->productBlockRelation->getFullName() . ' №' . $this->number;
        }
        elseif ($this->productNodeRelation) {
            return $this->productNodeRelation->getFullName() . ' №' . $this->number;
        }
        elseif ($this->productRelation) {
            return $this->productRelation->mark . ' №' . $this->number;
        }

        return null;
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
