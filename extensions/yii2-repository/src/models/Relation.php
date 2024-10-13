<?php

declare(strict_types=1);

namespace kilyanov\repository\models;

use kilyanov\behaviors\ActiveRecord;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%repository_relation}}".
 *
 * @property string $id
 * @property string $model
 * @property integer $identity
 * @property string $attribute
 * @property integer $repositoryId
 * @property string $createdAt
 * @property string $updatedAt
 *
 * @property Repository $repositoryRelation
 */
class Relation extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return '{{%repository_relation}}';
    }

    /**
     * @inheritdoc
     * @return RelationQuery the active query used by this AR class.
     */
    public static function find(): RelationQuery
    {
        return new RelationQuery(get_called_class());
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
            [['model', 'identity', 'attribute', 'repositoryId'], 'required'],
            [['identity', 'repositoryId'], 'string'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['model'], 'string', 'max' => 128],
            [['attribute'], 'string', 'max' => 64],
            [
                ['repositoryId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Repository::class,
                'targetAttribute' => ['repositoryId' => 'id'],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'model' => 'Модель',
            'identity' => 'Идентификатор',
            'attribute' => 'Атрибут',
            'repositoryId' => 'Репозиторий',
            'createdAt' => 'Дата создания',
            'updatedAt' => 'Дата изменения',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getRepositoryRelation(): ActiveQuery
    {
        return $this->hasOne(Repository::class, ['id' => 'repositoryId']);
    }
}
