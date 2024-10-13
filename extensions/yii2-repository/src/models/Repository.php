<?php

declare(strict_types=1);

namespace kilyanov\repository\models;

use kilyanov\behaviors\ActiveRecord;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%repository}}".
 *
 * @property string $id
 * @property string $title
 * @property string $src
 * @property array $meta
 * @property string $createdAt
 * @property string $updatedAt
 *
 * @property Relation[] $relationRelation
 */
class Repository extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return '{{%repository}}';
    }

    /**
     * @inheritdoc
     * @return RepositoryQuery the active query used by this AR class.
     */
    public static function find(): RepositoryQuery
    {
        return new RepositoryQuery(get_called_class());
    }

    /**
     * @return array
     */
    public function transactions(): array
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['title', 'src', 'meta'], 'required'],
            [['meta', 'createdAt', 'updatedAt'], 'safe'],
            [['title', 'src'], 'string', 'max' => 128],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'title' => 'Заголовок',
            'src' => 'Файл',
            'meta' => 'Метаданные',
            'createdAt' => 'Дата создания',
            'updatedAt' => 'Дата изменения',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getRelationRelation(): ActiveQuery
    {
        return $this->hasMany(Relation::class, ['repositoryId' => 'id']);
    }
}
