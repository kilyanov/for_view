<?php

declare(strict_types=1);

namespace app\modules\industry\models;

use app\common\database\traits\HiddenAttributeTrait;
use app\common\interface\HiddenAttributeInterface;
use app\modules\industry\models\query\OrderListQuery;
use app\modules\industry\models\query\OrderToProductQuery;
use app\modules\industry\models\query\RepairProductQuery;
use Exception;
use kilyanov\behaviors\ActiveRecord;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%order_to_product}}".
 *
 * @property string $id ID
 * @property string|null $orderId Заказ
 * @property string|null $productId Изделие
 * @property string|null $comment Комментарии
 * @property int $hidden Скрыт
 * @property int|null $sort Вес
 * @property string $createdAt
 * @property string $updatedAt
 *
 * @property OrderList $orderRelation
 * @property RepairProduct $productRelation
 *
 * @property-read null|string|array|float $fullName
 */
class OrderToProduct extends ActiveRecord implements HiddenAttributeInterface
{
    use HiddenAttributeTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%order_to_product}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['orderId', 'productId'], 'required'],
            [['comment'], 'string'],
            [['hidden', 'sort'], 'integer'],
            [['createdAt', 'updatedAt'], 'safe'],
            [
                ['orderId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => OrderList::class,
                'targetAttribute' => ['orderId' => 'id']
            ],
            [
                ['productId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => RepairProduct::class,
                'targetAttribute' => ['productId' => 'id']
            ],
            ['hidden', 'default', 'value' => HiddenAttributeInterface::HIDDEN_NO],
            ['comment', 'default', 'value' => null],
            [
                ['number', 'comment'],
                'trim',
                'when' => function($model, $attribute) {
                    return !empty($model->$attribute);
                }
            ],
            [
                'productId',
                'unique',
                'targetAttribute' => ['productId', 'orderId'],
                'message' => 'Изделие уже есть в заказе.'
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
            'orderId' => 'Заказ',
            'productId' => 'Изделие',
            'comment' => 'Комментарии',
            'hidden' => 'Скрыт',
            'sort' => 'Вес',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
        ];
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
     * Gets query for [[Product]].
     *
     * @return ActiveQuery|RepairProductQuery
     */
    public function getProductRelation(): ActiveQuery|RepairProductQuery
    {
        return $this->hasOne(RepairProduct::class, ['id' => 'productId']);
    }

    /**
     * {@inheritdoc}
     * @return OrderToProductQuery the active query used by this AR class.
     */
    public static function find(): OrderToProductQuery
    {
        return new OrderToProductQuery(get_called_class());
    }

    /**
     * @return array|string|float|null
     */
    public function getFullName(): array|string|float|null
    {
        return $this->productRelation->getFullName();
    }

    /**
     * @param array $model
     * @return string
     * @throws Exception
     */
    public static function getFullNameMoving(array $model): string
    {
        $product = ArrayHelper::getValue(self::getListProduct(), $model['productId']);

        return $product === null ? '' : $product;
    }

    /**
     * @return array
     */
    protected static function getListProduct(): array
    {
        static $list = [];

        if (empty($list)) {
            $list = RepairProduct::find()->hidden()->asDropDown();
        }

        return $list;
    }

    /**
     * @param array $config
     * @return array
     * @throws Exception
     */
    public static function asDropDown(array $config = []): array
    {
        /** @var self $query */
        $query = self::find()->hidden()->orderId(ArrayHelper::getValue($config,'orderId'));

        return $query->asDropDown();
    }
}
