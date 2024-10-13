<?php

declare(strict_types=1);

namespace app\modules\rationing\forms;

use app\common\database\traits\HiddenAttributeTrait;
use app\common\interface\HiddenAttributeInterface;
use app\modules\impact\models\Impact;
use app\modules\product\models\Product;
use app\modules\product\models\ProductBlock;
use app\modules\product\models\ProductNode;
use app\modules\rationing\models\RationingProduct;
use app\modules\rationing\models\RationingProductData;
use app\modules\unit\models\Unit;
use Yii;
use yii\base\Model;
use yii\db\Exception;

/**
 * @property string $rationingId Нормировка для копии
 * @property string $unitId Подразделение
 * @property string $impactId Вид воздействия
 * @property string $name Название
 * @property string $productId Изделие
 */
class RationingProductForm extends Model implements HiddenAttributeInterface
{
    use HiddenAttributeTrait;

    /**
     * @var string|null
     */
    public ?string $id = null;

    /**
     * @var string|null
     */
    public ?string $rationingId = null;

    /**
     * @var string|null
     */
    public ?string $unitId = null;

    /**
     * @var string|null
     */
    public ?string $impactId = null;

    /**
     * @var string|null
     */
    public ?string $name = null;

    /**
     * @var string|null
     */
    public ?string $productId = null;

    /**
     * @var string|null
     */
    public ?string $productNodeId = null;

    /**
     * @var string|null
     */
    public ?string $productBlockId = null;

    /**
     * @var int
     */
    public int $hidden = HiddenAttributeInterface::HIDDEN_NO;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['rationingId', 'unitId', 'name', 'impactId'], 'required'],
            [
                ['rationingId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => RationingProduct::class,
                'targetAttribute' => ['rationingId' => 'id']
            ],
            [
                ['unitId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Unit::class,
                'targetAttribute' => ['unitId' => 'id']
            ],
            [
                ['productId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Product::class,
                'targetAttribute' => ['productId' => 'id']
            ],
            [
                ['productBlockId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => ProductBlock::class,
                'targetAttribute' => ['productBlockId' => 'id']
            ],
            [
                ['productNodeId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => ProductNode::class,
                'targetAttribute' => ['productNodeId' => 'id']
            ],
            [
                ['impactId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Impact::class,
                'targetAttribute' => ['impactId' => 'id']
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
            'rationingId' => 'Нормировка для копии',
            'unitId' => 'Подразделение',
            'name' => 'Название',
            'productId' => 'Изделие',
            'productNodeId' => 'Система',
            'productBlockId' => 'Блок',
            'impactId' => 'Вид ремонта',
            'hidden' => 'Скрыт',
        ];
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function save(): bool
    {
        $attributes = $this->getAttributes();
        unset($attributes['rationingId']);
        $create = new RationingProduct($attributes);
        $transaction = Yii::$app->db->beginTransaction();
        if ($create->save()) {
            $models = RationingProductData::find()
                ->andWhere(['rationingId' => $this->rationingId])
                ->hidden()
                ->orderBy('sort')
                ->all();
            $error = true;
            foreach ($models as $model) {
                $add = new RationingProductData($model->getAttributes([
                    'type', 'point', 'subItem', 'name', 'machineId', 'unitId',
                    'ed', 'countItems', 'periodicity', 'category',
                    'norma', 'specialId', 'comment', 'sort', 'normaAll'
                ]));
                $add->rationingId = $create->id;
                if (!$add->save()) {
                    $error = false;
                    break;
                }
            }
            if (!$error) {
                $transaction->rollBack();

                return false;
            }
            else {
                $transaction->commit();

                return true;
            }
        }
        $transaction->rollBack();

        return false;
    }
}
