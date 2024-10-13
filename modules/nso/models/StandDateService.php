<?php

namespace app\modules\nso\models;

use app\common\database\traits\HiddenAttributeTrait;
use app\common\database\traits\MonthTrait;
use app\common\interface\HiddenAttributeInterface;
use app\modules\nso\behaviors\StandDateServiceBehavior;
use app\modules\nso\models\query\StandDateServiceQuery;
use app\modules\nso\models\query\StandQuery;
use kilyanov\behaviors\ActiveRecord;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%stand_date_service}}".
 *
 * @property string $id ID
 * @property string $standId Стенд
 * @property string|null $dateService Дата обслуживания
 * @property string|null $comment Примечание
 * @property int $hidden
 * @property string $createdAt
 * @property string $updatedAt
 *
 * @property Stand $standRelation
 *
 * @property-read null|string|array|float $fullName
 */
class StandDateService extends ActiveRecord implements HiddenAttributeInterface
{
    use HiddenAttributeTrait;
    use MonthTrait;

    /**
     * @return array
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        return ArrayHelper::merge(
            $behaviors, [
                'StandDateServiceBehavior' => [
                    'class' => StandDateServiceBehavior::class
                ]
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%stand_date_service}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['standId', 'dateService'], 'required'],
            [['dateService', 'createdAt', 'updatedAt'], 'safe'],
            [['comment'], 'string'],
            [['hidden'], 'integer'],
            [
                ['standId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Stand::class,
                'targetAttribute' => ['standId' => 'id']
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
            'standId' => 'Стенд',
            'dateService' => 'Дата обслуживания',
            'comment' => 'Примечание',
            'hidden' => 'Скрыт',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Stand]].
     *
     * @return ActiveQuery|StandQuery
     */
    public function getStandRelation(): ActiveQuery|StandQuery
    {
        return $this->hasOne(Stand::class, ['id' => 'standId']);
    }

    /**
     * {@inheritdoc}
     * @return StandDateServiceQuery the active query used by this AR class.
     */
    public static function find(): StandDateServiceQuery
    {
        return new StandDateServiceQuery(get_called_class());
    }

    /**
     * @return array|string|float|null
     */
    public function getFullName(): array|string|float|null
    {
        return $this->standRelation->getFullName();
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
        /** @var self $query */
        $query = self::find()->hidden();

        return $query->asDropDown();
    }
}
