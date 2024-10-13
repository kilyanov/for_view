<?php

declare(strict_types=1);

namespace app\modules\industry\models;

use app\modules\industry\models\query\PresentationBookDataDeviceRepairQuery;
use app\modules\industry\models\query\PresentationBookQuery;
use app\modules\rationing\models\query\RationingDeviceDataQuery;
use app\modules\rationing\models\query\RationingDeviceQuery;
use app\modules\rationing\models\RationingDevice;
use app\modules\rationing\models\RationingDeviceData;
use kilyanov\behaviors\ActiveRecord;
use kilyanov\behaviors\common\IdAttributeBehavior;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%presentation_book_data_device_repair}}".
 *
 * @property string $id ID
 * @property string $bookId Книга предъявления
 * @property string $rationingDeviceId Нормировка
 * @property string $rationingDeviceDataId Пункт нормировки
 * @property float|null $norma Н/Ч
 * @property int|null $sort Вес
 *
 * @property PresentationBook $bookRelation
 * @property RationingDevice $rationingDeviceRelation
 * @property RationingDeviceData $rationingDeviceDataRelation
 *
 * @property-read null|string|array|float $fullName
 */
class PresentationBookDataDeviceRepair extends ActiveRecord
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
        return '{{%presentation_book_data_device_repair}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['bookId', 'rationingDeviceId', 'rationingDeviceDataId'], 'required'],
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
                ['rationingDeviceDataId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => RationingDeviceData::class,
                'targetAttribute' => ['rationingDeviceDataId' => 'id']
            ],
            [
                ['rationingDeviceId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => RationingDevice::class,
                'targetAttribute' => ['rationingDeviceId' => 'id']
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
            'rationingDeviceId' => 'Нормировка',
            'rationingDeviceDataId' => 'Пункт нормировки',
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
     * Gets query for [[RationingDevice]].
     *
     * @return ActiveQuery|RationingDeviceQuery
     */
    public function getRationingDeviceRelation(): ActiveQuery|RationingDeviceQuery
    {
        return $this->hasOne(RationingDevice::class, ['id' => 'rationingDeviceId']);
    }

    /**
     * Gets query for [[RationingDeviceData]].
     *
     * @return ActiveQuery|RationingDeviceDataQuery
     */
    public function getRationingDeviceDataRelation(): RationingDeviceDataQuery|ActiveQuery
    {
        return $this->hasOne(RationingDeviceData::class, ['id' => 'rationingDeviceDataId']);
    }

    /**
     * {@inheritdoc}
     * @return PresentationBookDataDeviceRepairQuery the active query used by this AR class.
     */
    public static function find(): PresentationBookDataDeviceRepairQuery
    {
        return new PresentationBookDataDeviceRepairQuery(get_called_class());
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
