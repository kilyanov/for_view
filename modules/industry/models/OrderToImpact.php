<?php

declare(strict_types=1);

namespace app\modules\industry\models;

use app\common\database\traits\HiddenAttributeTrait;
use app\common\interface\HiddenAttributeInterface;
use app\modules\impact\models\Impact;
use app\modules\impact\models\query\ImpactQuery;
use app\modules\industry\models\query\OrderListQuery;
use app\modules\industry\models\query\OrderToImpactQuery;
use Exception;
use kilyanov\behaviors\ActiveRecord;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%order_to_impact}}".
 *
 * @property string $id ID
 * @property string|null $orderId Заказ
 * @property string|null $impactId Вид воздействия
 * @property int $hidden Скрыт
 * @property int|null $sort Вес
 * @property string $createdAt
 * @property string $updatedAt
 *
 * @property Impact $impactRelation
 * @property OrderList $orderRelation
 *
 * @property-read null|string|array|float $fullName
 */
class OrderToImpact extends ActiveRecord implements HiddenAttributeInterface
{
    use HiddenAttributeTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%order_to_impact}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['orderId', 'impactId'], 'required'],
            [['hidden', 'sort'], 'integer'],
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
            ['hidden', 'default', 'value' => HiddenAttributeInterface::HIDDEN_NO],
            [
                'impactId',
                'unique',
                'targetAttribute' => ['impactId', 'orderId'],
                'message' => 'Запись уже есть в заказе.'
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
            'impactId' => 'Вид воздействия',
            'hidden' => 'Скрыт',
            'sort' => 'Вес',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Impact]].
     *
     * @return ActiveQuery|ImpactQuery
     */
    public function getImpactRelation(): ActiveQuery|ImpactQuery
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
     * {@inheritdoc}
     * @return OrderToImpactQuery the active query used by this AR class.
     */
    public static function find(): OrderToImpactQuery
    {
        return new OrderToImpactQuery(get_called_class());
    }

    /**
     * @return array|string|float|null
     * @throws Exception
     */
    public function getFullName(): array|string|float|null
    {
        return $this->impactRelation->getFullName();
    }

    /**
     * @param array $model
     * @return string
     * @throws Exception
     */
    public static function getFullNameMoving(array $model): string
    {
        $impact = ArrayHelper::getValue(self::getListImpact(), $model['impactId']);

        return $impact === null ? '' : $impact;
    }

    /**
     * @return array
     */
    protected static function getListImpact(): array
    {
        static $list = [];

        if (empty($list)) {
            $list = Impact::find()->hidden()->asDropDown();
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
        $query = self::find()->hidden()->orderId(ArrayHelper::getValue($config,'orderId'));

        return $query->asDropDown();
    }
}
