<?php

declare(strict_types=1);

namespace app\modules\application\models;

use app\modules\application\behaviors\ApplicationDataBehavior;
use app\modules\application\models\query\ApplicationDataQuery;
use app\modules\application\models\query\ApplicationQuery;
use app\modules\resource\models\query\ResourceQuery;
use app\modules\resource\models\Resource;
use Exception;
use kilyanov\behaviors\ActiveRecord;
use kilyanov\behaviors\common\TagDependencyBehavior;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%application_data}}".
 *
 * @property string $id ID
 * @property string $applicationId Заявка
 * @property string $resourceId Ресурс
 * @property float|null $quantity Кол-во
 * @property int|null $mark ЗИП/Материалы
 * @property int|null $type 100% замена/По дефектации/ЗИП-0
 * @property string|null $comment Комментарии
 * @property string|null $deliveryTime Срок поставки
 * @property float|null $quantityReceipt Кол-во получено
 * @property string|null $receiptDate Дата получения
 * @property string|null $designation Обозначение по учету
 * @property string $createdAt
 * @property string|null $updatedAt
 *
 * @property Application $applicationRelation
 * @property Resource $resourceRelation
 *
 * @property-read null|string|array|float $fullName
 *
 *  //виртуальное поле
 * @property float $percent
 * @property-read string[] $colorRow
 * @property string $status
 */
class ApplicationData extends ActiveRecord
{
    const TYPE_ZERO = 0; //100% замена
    const TYPE_ONE = 1; //По дефектации
    const TYPE_TWO = 2; //ЗИП-0

    const MARK_ONE = 1; //ЗИП
    const MARK_TWO = 2; //Материалы

    /**
     * @var float
     */
    public float $percent = 0.00;

    /**
     * @var string
     */
    public string $status = '';

    /**
     * @return array
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        return ArrayHelper::merge(
            $behaviors, [
                'ApplicationDataBehavior' => [
                    'class' => ApplicationDataBehavior::class,
                ],
                'TagDependencyBehavior' => [
                    'class' => TagDependencyBehavior::class,
                ]
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%application_data}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['applicationId', 'resourceId'], 'required'],
            [['quantity', 'quantityReceipt'], 'number'],
            [['mark', 'type'], 'integer'],
            [['comment'], 'string'],
            [['deliveryTime', 'receiptDate', 'designation', 'createdAt', 'updatedAt'], 'safe'],
            [
                ['applicationId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Application::class,
                'targetAttribute' => ['applicationId' => 'id']
            ],
            [
                ['resourceId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Resource::class,
                'targetAttribute' => ['resourceId' => 'id']
            ],
            ['comment', 'default', 'value' => null],
            [
                ['number', 'comment'],
                'trim',
                'when' => function ($model, $attribute) {
                    return !empty($model->$attribute);
                }
            ],
            ['quantityReceipt', 'default', 'value' => 0],
            [
                'type',
                'in',
                'range' => array_keys(self::getTypeList())
            ],
            [
                'mark',
                'in',
                'range' => array_keys(self::getMarkList())
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
            'applicationId' => 'Заявка',
            'resourceId' => 'Ресурс',
            'quantity' => 'Кол-во заказано',
            'mark' => 'ЗИП/Материалы',
            'type' => '100% замена/По дефектации/ЗИП-0',
            'comment' => 'Комментарии',
            'deliveryTime' => 'Срок поставки',
            'quantityReceipt' => 'Кол-во получено',
            'receiptDate' => 'Дата получения',
            'designation' => 'Обозначение по учету',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
            //виртуальные поля
            'percent' => '% обесп.'
        ];
    }

    /**
     * Gets query for [[Application]].
     *
     * @return ActiveQuery|ApplicationQuery
     */
    public function getApplicationRelation(): ActiveQuery|ApplicationQuery
    {
        return $this->hasOne(Application::class, ['id' => 'applicationId']);
    }

    /**
     * Gets query for [[Resource]].
     *
     * @return ActiveQuery|ResourceQuery
     */
    public function getResourceRelation(): ActiveQuery|ResourceQuery
    {
        return $this->hasOne(Resource::class, ['id' => 'resourceId']);
    }

    /**
     * {@inheritdoc}
     * @return ApplicationDataQuery the active query used by this AR class.
     */
    public static function find(): ApplicationDataQuery
    {
        return new ApplicationDataQuery(get_called_class());
    }

    /**
     * @return string[]
     */
    public static function getTypeList(): array
    {
        return [
            self::TYPE_ZERO => '100% замена',
            self::TYPE_ONE => 'По дефектации',
            self::TYPE_TWO => 'ЗИП-0'
        ];
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::getTypeList()[$this->type];
    }

    /**
     * @return string[]
     */
    public static function getMarkList(): array
    {
        return [
            self::MARK_ONE => 'ЗИП',
            self::MARK_TWO => 'Материалы'
        ];
    }

    /**
     * @return string
     */
    public function getMark(): string
    {
        return self::getMarkList()[$this->mark] ?? '';
    }

    /**
     * @return array|string|float|null
     */
    public function getFullName(): array|string|float|null
    {
        return $this->resourceRelation->getFullName();
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
     * @throws Exception
     */
    public static function asDropDown(array $config = []): array
    {
        /** @var self $query */
        $query = self::find()
            ->application(ArrayHelper::getValue($config, 'applicationId'));

        return $query->asDropDown();
    }

    /**
     * @return string[]
     */
    public function getColorRow(): array
    {
        if ($this->percent === 0.00) {
            return ['class' => "table-danger"];
        }
        if ($this->percent === 100.00) {

            return ['class' => "table-success"];
        }
        return  [];
    }
}
