<?php

declare(strict_types=1);

namespace app\modules\rationing\models;

use app\common\database\traits\HiddenAttributeTrait;
use app\common\interface\HiddenAttributeInterface;
use app\modules\rationing\models\query\RationingDeviceDataQuery;
use app\modules\rationing\models\query\RationingDeviceQuery;
use Exception;
use kilyanov\behaviors\ActiveRecord;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%rationing_device}}".
 *
 * @property string $id ID
 * @property string $paragraph Параграф
 * @property string $name Название работ
 * @property float|null $norma Н/Ч
 * @property int|null $sort Вес
 * @property int $hidden
 * @property string $createdAt
 * @property string $updatedAt
 *
 * @property RationingDeviceData[] $rationingDeviceDatasRelation
 *
 * @property-read string $fullName
 */
class RationingDevice extends ActiveRecord implements HiddenAttributeInterface
{
    use HiddenAttributeTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%rationing_device}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['paragraph', 'name'], 'required'],
            [['name'], 'string'],
            [['norma'], 'number'],
            [['sort', 'hidden'], 'integer'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['paragraph'], 'string', 'max' => 255],
            [
                ['name', 'paragraph', 'comment'],
                'trim',
                'when' => function ($model, $attribute) {
                    return !empty($model->$attribute);
                }
            ],
            ['hidden', 'default', 'value' => HiddenAttributeInterface::HIDDEN_NO],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'paragraph' => 'Параграф',
            'name' => 'Название работ',
            'norma' => 'Н/Ч',
            'sort' => 'Вес',
            'hidden' => 'Скрыт',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[RationingDeviceDatas]].
     *
     * @return ActiveQuery|RationingDeviceDataQuery
     */
    public function getRationingDeviceDatasRelation(): RationingDeviceDataQuery|ActiveQuery
    {
        return $this->hasMany(RationingDeviceData::class, ['rationingDeviceId' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return RationingDeviceQuery the active query used by this AR class.
     */
    public static function find(): RationingDeviceQuery
    {
        return new RationingDeviceQuery(get_called_class());
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return $this->paragraph . '. ' . $this->name . ' ' . '(' . $this->norma . ')';
    }

    /**
     * @param array $model
     * @return string
     */
    public static function getFullNameMoving(array $model): string
    {
        return $model['paragraph'] . '. ' . $model['name'] . ' ' . '(' . $model['norma'] . ')';
    }

    /**
     * @param array $config
     * @return array
     * @throws Exception
     */
    public static function asDropDown(array $config = []): array
    {
        /** @var self $query */
        $query = self::find()->hidden();
        if ($limit = ArrayHelper::getValue($config, 'limit')) {
            $query->limit($limit);
        }

        return $query->asDropDown();
    }
}
