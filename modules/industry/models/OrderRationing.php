<?php

declare(strict_types=1);

namespace app\modules\industry\models;

use app\common\database\traits\HiddenAttributeTrait;
use app\common\interface\HiddenAttributeInterface;
use app\modules\impact\models\Impact;
use app\modules\impact\models\query\ImpactQuery;
use app\modules\industry\models\query\OrderListQuery;
use app\modules\industry\models\query\OrderRationingDataQuery;
use app\modules\industry\models\query\OrderRationingQuery;
use app\modules\product\models\Product;
use app\modules\product\models\ProductBlock;
use app\modules\product\models\ProductNode;
use app\modules\product\models\query\ProductBlockQuery;
use app\modules\product\models\query\ProductNodeQuery;
use app\modules\product\models\query\ProductQuery;
use app\modules\unit\models\query\UnitQuery;
use app\modules\unit\models\Unit;
use kilyanov\behaviors\ActiveRecord;
use Yii;
use yii\db\ActiveQuery;
use yii\web\Application;

/**
 * This is the model class for table "{{%order_rationing}}".
 *
 * @property string $id ID
 * @property string|null $orderId Заказ
 * @property string $name Название
 * @property string|null $productId Изделие
 * @property string|null $productNodeId Система
 * @property string|null $productBlockId Блок
 * @property string $unitId Подразделение
 * @property string $impactId Вид ремонта
 * @property float|null $norma Н/Ч
 * @property string|null $comment Комментарии
 * @property int $hidden
 * @property string $createdAt
 * @property string|null $updatedAt
 *
 * @property Impact $impactRelation
 * @property OrderList $orderRelation
 * @property OrderRationingData[] $orderRationingDatasRelation
 * @property Product $productRelation
 * @property ProductBlock $productBlockRelation
 * @property ProductNode $productNodeRelation
 * @property Unit $unitRelation
 *
 * @property-read null|string $productName
 * @property-read null|string|array|float $fullName
 */
class OrderRationing extends ActiveRecord implements HiddenAttributeInterface
{
    use HiddenAttributeTrait;

    /**
     * @var string|null
     */
    public ?string $rationingId = null;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%order_rationing}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'unitId', 'impactId',], 'required'],
            [
                ['rationingId',],
                'required',
                'when' => function ($model, $attribute) {
                    return $model->isNewRecord && Yii::$app instanceof Application;
                }
            ],
            [['name', 'comment'], 'string'],
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
                ['orderId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => OrderList::class,
                'targetAttribute' => ['orderId' => 'id']
            ],
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
            ['productNodeId', 'default', 'value' => null],
            ['productBlockId', 'default', 'value' => null],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'orderId' => 'Заказ',
            'name' => 'Название',
            'productId' => 'Изделие',
            'productNodeId' => 'Система',
            'productBlockId' => 'Блок',
            'unitId' => 'Подразделение',
            'impactId' => 'Вид ремонта',
            'norma' => 'Н/Ч',
            'comment' => 'Коментарии',
            'hidden' => 'Скрыт',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
            /** виртуальные поля */
            'rationingId' => 'Нормировка',
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
     * Gets query for [[Order]].
     *
     * @return ActiveQuery|OrderListQuery
     */
    public function getOrderRelation(): ActiveQuery|OrderListQuery
    {
        return $this->hasOne(OrderList::class, ['id' => 'orderId']);
    }

    /**
     * Gets query for [[OrderRationingDatas]].
     *
     * @return ActiveQuery|OrderRationingDataQuery
     */
    public function getOrderRationingDatasRelation(): OrderRationingDataQuery|ActiveQuery
    {
        return $this->hasMany(OrderRationingData::class, ['rationingId' => 'id']);
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
     * @return OrderRationingQuery the active query used by this AR class.
     */
    public static function find(): OrderRationingQuery
    {
        return new OrderRationingQuery(get_called_class());
    }

    /**
     * @return array|string|float|null
     */
    public function getFullName(): array|string|float|null
    {
        $result = [];
        $result[] = $this->name;
        $result[] = '(' . $this->norma . ')';
        $result[] = $this->getProductName();
        $result[] = 'заказ № ' . $this->orderRelation->getFullName();

        return implode(' ', array_filter($result));
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

    /**
     * @return string|null
     */
    private function getProductName(): ?string
    {
        if ($this->productBlockRelation) {
            return 'изд. ' . $this->productBlockRelation->getFullName();
        }
        elseif ($this->productNodeRelation) {
            return 'изд. ' . $this->productNodeRelation->getFullName();
        }
        elseif ($this->productRelation) {
            return 'изд. ' . $this->productRelation->mark;
        }

        return null;
    }
}
