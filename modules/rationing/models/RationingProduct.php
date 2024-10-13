<?php

declare(strict_types=1);

namespace app\modules\rationing\models;

use app\common\database\traits\HiddenAttributeTrait;
use app\common\interface\HiddenAttributeInterface;
use app\modules\impact\models\Impact;
use app\modules\impact\models\query\ImpactQuery;
use app\modules\product\models\Product;
use app\modules\product\models\ProductBlock;
use app\modules\product\models\ProductNode;
use app\modules\product\models\query\ProductBlockQuery;
use app\modules\product\models\query\ProductNodeQuery;
use app\modules\product\models\query\ProductQuery;
use app\modules\rationing\models\query\RationingProductDataQuery;
use app\modules\rationing\models\query\RationingProductQuery;
use app\modules\unit\models\query\UnitQuery;
use app\modules\unit\models\Unit;
use Exception;
use kilyanov\behaviors\ActiveRecord;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%rationing_product}}".
 *
 * @property string $id ID
 * @property string $name Название
 * @property string|null $productId Изделие
 * @property string|null $productNodeId Система
 * @property string|null $productBlockId Блок
 * @property string $unitId Подразделение
 * @property string $impactId Вид ремонта
 * @property float|null $norma Н/Ч
 * @property string|null $comment Коментарии
 * @property int $hidden
 * @property string $createdAt
 * @property string|null $updatedAt
 *
 * @property Impact $impactRelation
 * @property Product $productRelation
 * @property ProductBlock $productBlockRelation
 * @property ProductNode $productNodeRelation
 * @property RationingProductData[] $rationingProductDatasRelation
 * @property Unit $unitRelation
 *
 * @property-read string $fullName
 */
class RationingProduct extends ActiveRecord implements HiddenAttributeInterface
{
    use HiddenAttributeTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%rationing_product}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'unitId', 'impactId',], 'required'],
            [['name', 'comment'], 'string'],
            ['norma', 'default', 'value' => 0.00],
            [['norma'], 'number'],
            [['hidden'], 'integer'],
            [['createdAt', 'updatedAt'], 'safe'],
            [
                ['impactId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Impact::class,
                'targetAttribute' => ['impactId' => 'id']
            ],
            [
                ['productId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Product::class,
                'targetAttribute' => ['productId' => 'id']
            ],
            [
                ['productBlockId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => ProductBlock::class,
                'targetAttribute' => ['productBlockId' => 'id']
            ],
            [
                ['productNodeId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => ProductNode::class,
                'targetAttribute' => ['productNodeId' => 'id']
            ],
            [
                ['unitId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Unit::class,
                'targetAttribute' => ['unitId' => 'id']
            ],
            [
                ['name', 'comment'],
                'trim',
                'when' => function ($model, $attribute) {
                    return !empty($model->$attribute);
                }
            ],
            ['hidden', 'default', 'value' => HiddenAttributeInterface::HIDDEN_NO],
            ['productBlockId', 'default', 'value' => null],
            ['productNodeId', 'default', 'value' => null],
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
            'productId' => 'Изделие',
            'productNodeId' => 'Система',
            'productBlockId' => 'Блок',
            'unitId' => 'Подразделение',
            'impactId' => 'Вид ремонта',
            'norma' => 'Н/Ч',
            'comment' => 'Комментарии',
            'hidden' => 'Скрыт',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Impact]].
     *
     * @return ActiveQuery|ImpactQuery
     */
    public function getImpactRelation(): ImpactQuery|ActiveQuery
    {
        return $this->hasOne(Impact::class, ['id' => 'impactId']);
    }

    /**
     * Gets query for [[Product]].
     *
     * @return ActiveQuery|ProductQuery
     */
    public function getProductRelation(): ProductQuery|ActiveQuery
    {
        return $this->hasOne(Product::class, ['id' => 'productId']);
    }

    /**
     * Gets query for [[ProductBlock]].
     *
     * @return ActiveQuery|ProductBlockQuery
     */
    public function getProductBlockRelation(): ProductBlockQuery|ActiveQuery
    {
        return $this->hasOne(ProductBlock::class, ['id' => 'productBlockId']);
    }

    /**
     * Gets query for [[ProductNode]].
     *
     * @return ActiveQuery|ProductNodeQuery
     */
    public function getProductNodeRelation(): ProductNodeQuery|ActiveQuery
    {
        return $this->hasOne(ProductNode::class, ['id' => 'productNodeId']);
    }

    /**
     * Gets query for [[RationingProductDatas]].
     *
     * @return ActiveQuery|RationingProductDataQuery
     */
    public function getRationingProductDatasRelation(): ActiveQuery|RationingProductDataQuery
    {
        return $this->hasMany(RationingProductData::class, ['rationingId' => 'id']);
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
     * @return RationingProductQuery the active query used by this AR class.
     */
    public static function find(): RationingProductQuery
    {
        return new RationingProductQuery(get_called_class());
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return $this->name . ' ' . '(' . $this->norma . ') изд. ' . $this->productRelation->getFullName();
    }

    /**
     * @param array $model
     * @return string
     */
    public static function getFullNameMoving(array $model): string
    {
        $products = self::getProductList();
        return $model['name'] . ' ' . '(' . $model['norma'] . ') изд. ' . $products[$model['productId']];
    }

    /**
     * @return array
     */
    protected static function getProductList(): array
    {
        static $products = [];

        if (empty($products)) {
            $products = Product::asDropDown();
        }

        return $products;
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
        if ($limit = ArrayHelper::getValue($config, 'limit')) {
            $query->limit($limit);
        }

        return $query->asDropDown();
    }
}
