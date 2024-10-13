<?php

declare(strict_types=1);

namespace app\modules\resource\models;

use app\common\database\traits\HiddenAttributeTrait;
use app\common\interface\HiddenAttributeInterface;
use app\modules\resource\models\query\ResourceQuery;
use kilyanov\behaviors\ActiveRecord;

/**
 * This is the model class for table "tbl_resource".
 *
 * @property string $id ID
 * @property string $name Наименование
 * @property string|null $mark Чертеж, ГОСТ, ТУ
 * @property string|null $ed Ед. изм.
 * @property string|null $stamp Обозначение
 * @property string|null $size Размер
 * @property string|null $description Примечание
 * @property int $hidden
 * @property string $createdAt
 * @property string $updatedAt
 *
 * @property-read string $fullName
 */
class Resource extends ActiveRecord implements HiddenAttributeInterface
{
    use HiddenAttributeTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%resource}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name',], 'required'],
            [['name', 'ed', 'description'], 'string'],
            [['hidden'], 'integer'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['mark', 'stamp', 'size'], 'string', 'max' => 255],
            ['hidden', 'default', 'value' => HiddenAttributeInterface::HIDDEN_NO],
            [
                ['name', 'ed', 'description', 'mark', 'stamp', 'size'],
                'trim',
                'when' => function($model, $attribute) {
                    return !empty($model->$attribute);
                }
            ],
            [
                ['name', 'ed', 'description', 'mark', 'stamp', 'size'],
                'unique',
                'targetAttribute' => ['name', 'ed', 'description', 'mark', 'stamp', 'size']
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
            'name' => 'Наименование',
            'mark' => 'Чертеж, ГОСТ, ТУ',
            'ed' => 'Ед. изм.',
            'stamp' => 'Обозначение',
            'size' => 'Размер',
            'description' => 'Примечание',
            'hidden' => 'Скрыт',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
        ];
    }

    /**
     * {@inheritdoc}
     * @return ResourceQuery the active query used by this AR class.
     */
    public static function find(): ResourceQuery
    {
        return new ResourceQuery(get_called_class());
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return implode(' ', array_filter([
            $this->name, $this->mark, $this->stamp, $this->size
        ]));
    }

    /**
     * @param array $model
     * @return string
     */
    public static function getFullNameMoving(array $model): string
    {
        implode(' ', array_filter([
            $model['name'], $model['mark'], $model['stamp'], $model['size']
        ]));
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
