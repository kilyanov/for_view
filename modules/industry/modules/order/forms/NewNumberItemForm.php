<?php

declare(strict_types=1);

namespace app\modules\industry\modules\order\forms;

use yii\base\Model;

class NewNumberItemForm extends Model
{
    /**
     * @var int|null
     */
    public ?int $number = null;

    /**
     * @var array
     */
    public array $orderRationingDataId = [];

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['number',], 'required'],
            [['number'], 'integer'],
            [['orderRationingDataId'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'number' => 'Новый номер пункта',
        ];
    }
}
