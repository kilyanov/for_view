<?php

declare(strict_types=1);

namespace app\modules\contract\models;

use app\common\database\traits\HiddenAttributeTrait;
use app\common\interface\HiddenAttributeInterface;
use app\modules\contract\models\query\ContractQuery;
use app\modules\product\models\Product;
use app\modules\product\models\ProductBlock;
use app\modules\product\models\ProductNode;
use app\modules\contract\models\query\ContractSpecificationQuery;
use kilyanov\behaviors\ActiveRecord;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%contract_specification}}".
 *
 * @property string $id ID
 * @property string $contractId Контракт
 * @property string $productId Изделие
 * @property string|null $productNodeId Система, узел
 * @property string|null $productBlockId Блок
 * @property string $factoryNumber Заводской номер
 * @property int $hidden
 * @property string|null $comment Примечание
 * @property string $createdAt
 * @property string $updatedAt
 *
 * @property Contract $contractRelation
 * @property Product $productRelation
 * @property ProductBlock $productBlockRelation
 * @property ProductNode $productNodeRelation
 *
 * @property-read null|string|array|float $fullName
 */
class ContractSpecification extends ActiveRecord implements HiddenAttributeInterface
{
    use HiddenAttributeTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%contract_specification}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['contractId', 'productId', 'factoryNumber',], 'required'],
            [['hidden'], 'integer'],
            [['comment'], 'string'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['factoryNumber'], 'string', 'max' => 255],
            [
                ['contractId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Contract::class,
                'targetAttribute' => ['contractId' => 'id']
            ],
            [
                ['productBlockId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => ProductBlock::class,
                'targetAttribute' => ['productBlockId' => 'id']
            ],
            [
                ['productId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Product::class,
                'targetAttribute' => ['productId' => 'id']
            ],
            [
                ['productNodeId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => ProductNode::class,
                'targetAttribute' => ['productNodeId' => 'id']
            ],
            ['hidden', 'default', 'value' => HiddenAttributeInterface::HIDDEN_NO],
            [['comment', 'productBlockId', 'productNodeId'], 'default', 'value' => null],
            [
                ['factoryNumber', 'comment'],
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
            'contractId' => 'Контракт',
            'productId' => 'Изделие',
            'productNodeId' => 'Система, узел',
            'productBlockId' => 'Блок',
            'factoryNumber' => 'Заводской номер',
            'hidden' => 'Скрыт',
            'comment' => 'Примечание',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getContractRelation(): ActiveQuery
    {
        return $this->hasOne(Contract::class, ['id' => 'contractId']);
    }

    /**
     * @return ActiveQuery
     */
    public function getProductRelation(): ActiveQuery
    {
        return $this->hasOne(Product::class, ['id' => 'productId']);
    }

    /**
     * @return ActiveQuery
     */
    public function getProductBlockRelation(): ActiveQuery
    {
        return $this->hasOne(ProductBlock::class, ['id' => 'productBlockId']);
    }

    /**
     * @return ActiveQuery
     */
    public function getProductNodeRelation(): ActiveQuery
    {
        return $this->hasOne(ProductNode::class, ['id' => 'productNodeId']);
    }

    /**
     * @return ContractSpecificationQuery
     */
    public static function find(): ContractSpecificationQuery
    {
        return new ContractSpecificationQuery(get_called_class());
    }

    /**
     * @return array|string|float|null
     */
    public function getFullName(): array|string|float|null
    {
        $result = [];
        $result[] = $this->productRelation->getFullName();
        $result[] = $this->productNodeRelation->getFullName();
        $result[] = $this->productBlockRelation->getFullName();
        $result[] = 'зав. № ' . $this->factoryNumber;

        return implode(' ', array_filter($result));
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
