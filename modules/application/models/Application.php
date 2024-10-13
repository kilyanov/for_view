<?php

declare(strict_types=1);

namespace app\modules\application\models;

use app\common\database\traits\HiddenAttributeTrait;
use app\common\interface\HiddenAttributeInterface;
use app\modules\application\behaviors\ApplicationBehavior;
use app\modules\application\models\query\ApplicationDataQuery;
use app\modules\application\models\query\ApplicationQuery;
use app\modules\industry\models\OrderList;
use app\modules\industry\models\OrderToProduct;
use app\modules\industry\models\query\OrderListQuery;
use app\modules\industry\models\query\OrderToProductQuery;
use app\modules\unit\models\query\UnitQuery;
use app\modules\unit\models\Unit;
use Exception;
use kilyanov\behaviors\ActiveRecord;
use kilyanov\behaviors\common\TagDependencyBehavior;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%application}}".
 *
 * @property string $id ID
 * @property string $orderId Заказ
 * @property string|null $productId Изделие
 * @property string $unitId Подразделение
 * @property string $number Номер
 * @property string $dateFiling Дата обеспечения
 * @property int $hidden
 * @property string|null $comment Примечание
 * @property string $createdAt
 * @property string $updatedAt
 *
 * @property ApplicationData[] $applicationDatasRelation
 * @property OrderList $orderRelation
 * @property OrderToProduct $productRelation
 * @property Unit $unitRelation
 *
 * @property-read null|string|array|float $fullName
 *
 *  //виртуальное поле
 * @property float $percent
 */
class Application extends ActiveRecord implements HiddenAttributeInterface
{
    use HiddenAttributeTrait;

    /**
     * @var float
     */
    public float $percent = 0.00;

    /**
     * @return array
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        return ArrayHelper::merge(
            $behaviors,
            [
                'ApplicationBehavior' => [
                    'class' => ApplicationBehavior::class,
                ],
                'TagDependencyBehavior' => [
                    'class' => TagDependencyBehavior::class,
                ]
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%application}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['orderId', 'unitId', 'number', 'dateFiling',], 'required'],
            [['dateFiling', 'createdAt', 'updatedAt'], 'safe'],
            [['hidden'], 'integer'],
            [['comment'], 'string'],
            [['number'], 'string', 'max' => 255],
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
                'targetClass' => OrderToProduct::class,
                'targetAttribute' => ['productId' => 'id']
            ],
            [
                ['unitId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Unit::class,
                'targetAttribute' => ['unitId' => 'id']
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
            'unitId' => 'Подразделение',
            'number' => 'Номер',
            'dateFiling' => 'Дата',
            'hidden' => 'Скрыт',
            'comment' => 'Примечание',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
            /** виртуальные поля */
            'percent' => '% обесп.',
        ];
    }

    /**
     * Gets query for [[ApplicationDatas]].
     *
     * @return ActiveQuery|ApplicationDataQuery
     */
    public function getApplicationDatasRelation(): ActiveQuery|ApplicationDataQuery
    {
        return $this->hasMany(ApplicationData::class, ['applicationId' => 'id']);
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
     * @return ActiveQuery|OrderToProductQuery
     */
    public function getProductRelation(): ActiveQuery|OrderToProductQuery
    {
        return $this->hasOne(OrderToProduct::class, ['id' => 'productId']);
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
     * @return ApplicationQuery the active query used by this AR class.
     */
    public static function find(): ApplicationQuery
    {
        return new ApplicationQuery(get_called_class());
    }

    /**
     * @return array|string|float|null
     */
    public function getFullName(): array|string|float|null
    {
        return $this->number . ' ' . $this->dateFiling . ' г.';
    }

    /**
     * @param array $model
     * @return string
     */
    public static function getFullNameMoving(array $model): string
    {
        return $model['number'] . ' ' . $model['dateFiling'] . ' г.';
    }

    /**
     * @param array $config
     * @return array
     * @throws Exception
     */
    public static function asDropDown(array $config = []): array
    {
        /** @var self $query */
        $query = self::find()
            ->hidden()
            ->orderId(ArrayHelper::getValue($config, 'orderId'))
            ->product(ArrayHelper::getValue($config, 'productId'));

        return $query->asDropDown();
    }
}
