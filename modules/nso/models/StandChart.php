<?php

declare(strict_types=1);

namespace app\modules\nso\models;

use app\common\database\traits\HiddenAttributeTrait;
use app\common\database\traits\MonthTrait;
use app\common\interface\HiddenAttributeInterface;
use app\modules\nso\behaviors\StandChartBehavior;
use app\modules\nso\models\query\StandChartQuery;
use app\modules\nso\models\query\StandQuery;
use Exception;
use kilyanov\behaviors\ActiveRecord;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%stand_chart}}".
 *
 * @property string $id ID
 * @property string $standId Стенд
 * @property int $year Год
 * @property int $monthPlan Плановый месяц
 * @property int|null $monthFact Фактический месяц
 * @property string|null $dateFact Фактическая дата
 * @property int $hidden
 * @property string|null $comment Примечание
 * @property string $createdAt
 * @property string $updatedAt
 *
 * @property Stand $standRelation
 *
 * @property-read string $fullName
 */
class StandChart extends ActiveRecord implements HiddenAttributeInterface
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
                'StandChartBehavior' => [
                    'class' => StandChartBehavior::class
                ]
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%stand_chart}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['standId', 'year', 'monthPlan',], 'required'],
            [['year', 'monthPlan', 'monthFact', 'hidden'], 'integer'],
            [['dateFact', 'createdAt', 'updatedAt'], 'safe'],
            [['comment'], 'string'],
            [
                ['standId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Stand::class,
                'targetAttribute' => ['standId' => 'id']
            ],
            ['hidden', 'default', 'value' => HiddenAttributeInterface::HIDDEN_NO],
            [
                ['comment'],
                'trim',
                'when' => function ($model, $attribute) {
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
            'standId' => 'Стенд',
            'year' => 'Год',
            'monthPlan' => 'Плановый месяц',
            'monthFact' => 'Фактический месяц',
            'dateFact' => 'Фактическая дата',
            'hidden' => 'Скрыт',
            'comment' => 'Примечание',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
            /** virtual */
            'conservation' => 'Консервация',
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
     * @return StandChartQuery the active query used by this AR class.
     */
    public static function find(): StandChartQuery
    {
        return new StandChartQuery(get_called_class());
    }

    /**
     * @return string
     */
    public function getFullName(): string
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
     * @return string
     */
    public function getMonthPlan(): string
    {
        return $this->monthPlan === null ? '' : self::getMonthList()[$this->monthPlan];
    }

    /**
     * @return string
     */
    public function getMonthFact(): string
    {
        return $this->monthFact === null ? '' : self::getMonthList()[$this->monthFact];
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
            ->monthPlan(ArrayHelper::getValue($config, 'monthPlan'))
            ->monthFact(ArrayHelper::getValue($config, 'monthFact'));

        return $query->asDropDown();
    }
}
