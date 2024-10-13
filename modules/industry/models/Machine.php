<?php

declare(strict_types=1);

namespace app\modules\industry\models;

use app\common\database\traits\HiddenAttributeTrait;
use app\common\interface\HiddenAttributeInterface;
use app\modules\industry\models\query\MachineQuery;
use app\modules\product\models\Product;
use app\modules\product\models\query\ProductQuery;
use kilyanov\behaviors\ActiveRecord;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%machine}}".
 *
 * @property string $id ID
 * @property string|null $productId Изделие
 * @property string|null $number Номер
 * @property string $name Название
 * @property string|null $comment Примечание
 * @property int $hidden
 * @property int|null $sort Сортировка
 * @property string $createdAt
 * @property string $updatedAt
 * @property Product $productRelation
 *
 * @property-read string $fullName
 */
class Machine extends ActiveRecord implements HiddenAttributeInterface
{
    use HiddenAttributeTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%machine}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', ], 'required'],
            [['comment'], 'string'],
            [['hidden', 'sort'], 'integer'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['number', 'name'], 'string', 'max' => 255],
            [
                ['productId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Product::class,
                'targetAttribute' => ['productId' => 'id']
            ],
            ['hidden', 'default', 'value' => HiddenAttributeInterface::HIDDEN_NO],
            ['comment', 'default', 'value' => null],
            [
                ['number', 'name', 'comment'],
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
            'number' => 'Номер',
            'name' => 'Название',
            'comment' => 'Примечание',
            'hidden' => 'Скрыт',
            'sort' => 'Сортировка',
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
     * {@inheritdoc}
     * @return MachineQuery the active query used by this AR class.
     */
    public static function find(): MachineQuery
    {
        return new MachineQuery(get_called_class());
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return $this->number . '. ' . $this->name;
    }

    /**
     * @param array $model
     * @return string
     */
    public static function getFullNameMoving(array $model): string
    {
        return $model['number'] . '. ' . $model['name'];
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
