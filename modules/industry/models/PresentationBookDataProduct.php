<?php

declare(strict_types=1);

namespace app\modules\industry\models;

use app\modules\industry\models\query\OrderRationingDataQuery;
use app\modules\industry\models\query\OrderRationingQuery;
use app\modules\industry\models\query\PresentationBookDataProductQuery;
use app\modules\industry\models\query\PresentationBookQuery;
use kilyanov\behaviors\ActiveRecord;
use kilyanov\behaviors\common\IdAttributeBehavior;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%presentation_book_data_product}}".
 *
 * @property string $id ID
 * @property string $bookId Книга предъявления
 * @property string $orderRationingId Нормировка
 * @property string $orderRationingDataId Пункт нормировки
 * @property float|null $norma Н/Ч
 * @property int|null $sort Вес
 *
 * @property PresentationBook $bookRelation
 * @property OrderRationing $orderRationingRelation
 * @property OrderRationingData $orderRationingDataRelation
 *
 * @property-read null|string|array|float $fullName
 */
class PresentationBookDataProduct extends ActiveRecord
{
    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            'IdAttributeBehavior' => [
                'class' => IdAttributeBehavior::class,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%presentation_book_data_product}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['bookId', 'orderRationingId', 'orderRationingDataId'], 'required'],
            [['norma'], 'number'],
            [['sort'], 'integer'],
            [
                ['bookId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => PresentationBook::class,
                'targetAttribute' => ['bookId' => 'id']
            ],
            [
                ['orderRationingDataId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => OrderRationingData::class,
                'targetAttribute' => ['orderRationingDataId' => 'id']
            ],
            [
                ['orderRationingId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => OrderRationing::class,
                'targetAttribute' => ['orderRationingId' => 'id']
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
            'bookId' => 'Книга предъявления',
            'orderRationingId' => 'Нормировка',
            'orderRationingDataId' => 'Пункт нормировки',
            'norma' => 'Н/Ч',
            'sort' => 'Вес',
        ];
    }

    /**
     * Gets query for [[Book]].
     *
     * @return ActiveQuery|PresentationBookQuery
     */
    public function getBookRelation(): ActiveQuery|PresentationBookQuery
    {
        return $this->hasOne(PresentationBook::class, ['id' => 'bookId']);
    }

    /**
     * Gets query for [[OrderRationing]].
     *
     * @return ActiveQuery|OrderRationingQuery
     */
    public function getOrderRationingRelation(): ActiveQuery|OrderRationingQuery
    {
        return $this->hasOne(OrderRationing::class, ['id' => 'orderRationingId']);
    }

    /**
     * Gets query for [[OrderRationingData]].
     *
     * @return ActiveQuery|OrderRationingDataQuery
     */
    public function getOrderRationingDataRelation(): OrderRationingDataQuery|ActiveQuery
    {
        return $this->hasOne(OrderRationingData::class, ['id' => 'orderRationingDataId']);
    }

    /**
     * {@inheritdoc}
     * @return PresentationBookDataProductQuery the active query used by this AR class.
     */
    public static function find(): PresentationBookDataProductQuery
    {
        return new PresentationBookDataProductQuery(get_called_class());
    }

    /**
     * @return array|string|float|null
     */
    public function getFullName(): array|string|float|null
    {
        return '';
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
        return [];
    }
}
