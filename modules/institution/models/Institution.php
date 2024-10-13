<?php

declare(strict_types=1);

namespace app\modules\institution\models;

use app\common\database\traits\HiddenAttributeTrait;
use app\common\interface\HiddenAttributeInterface;
use app\modules\institution\models\query\InstitutionQuery;
use kilyanov\behaviors\ActiveRecord;

/**
 * This is the model class for table "{{%institution}}".
 *
 * @property string $id ID
 * @property string $name Название
 * @property string|null $address Адрес
 * @property string|null $requisites Реквизиты
 * @property string|null $description Примечание
 * @property int $hidden
 * @property int $sort
 * @property string $createdAt
 * @property string $updatedAt
 *
 * @property-read null|string|array|float $fullName
 */
class Institution extends ActiveRecord implements HiddenAttributeInterface
{
    use HiddenAttributeTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%institution}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name',], 'required'],
            [['address', 'requisites', 'description'], 'string'],
            [['hidden', 'sort'], 'integer'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['name'], 'string', 'max' => 255],
            ['hidden', 'default', 'value' => HiddenAttributeInterface::HIDDEN_NO],
            ['description', 'default', 'value' => null],
            [
                ['name', 'address', 'requisites', 'description'],
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
            'name' => 'Название',
            'address' => 'Адрес',
            'requisites' => 'Реквизиты',
            'description' => 'Примечание',
            'hidden' => 'Скрыт',
            'sort' => 'Сортировка',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
        ];
    }

    /**
     * @return InstitutionQuery
     */
    public static function find(): InstitutionQuery
    {
        return new InstitutionQuery(get_called_class());
    }

    /**
     * @return array|string|float|null
     */
    public function getFullName(): array|string|float|null
    {
        return $this->name;
    }

    /**
     * @param array $model
     * @return string
     */
    public static function getFullNameMoving(array $model): string
    {
        return $model['name'];
    }

    /**
     * @param array $config
     * @return array
     */
    public static function asDropDown(array $config = []): array
    {
        $query = self::find()->hidden();

        return $query->asDropDown();
    }
}
