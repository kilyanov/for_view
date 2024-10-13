<?php

declare(strict_types=1);

namespace app\modules\industry\modules\presentation\forms;

use app\modules\industry\models\PresentationBook;
use app\modules\industry\models\PresentationBookDataDeviceRepair;
use app\modules\rationing\models\RationingDevice;
use app\modules\rationing\models\RationingDeviceData;
use Yii;
use yii\base\Model;
use yii\db\Exception;

/**
 * @property string $bookId Книга предъявления
 * @property string $rationingDeviceId Нормировка
 */

class PresentBookDataDeviceRepairForm extends Model
{
    /**
     * @var string|null
     */
    public ?string $id = null;

    /**
     * @var string|null
     */
    public ?string $bookId = null;

    /**
     * @var string|null
     */
    public ?string $rationingDeviceId = null;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['bookId', 'rationingDeviceId'], 'required'],
            [
                ['bookId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => PresentationBook::class,
                'targetAttribute' => ['bookId' => 'id']
            ],
            [
                ['rationingDeviceId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => RationingDevice::class,
                'targetAttribute' => ['rationingDeviceId' => 'id']
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'bookId' => 'Книга предъявления',
            'rationingDeviceId' => 'Пункт',
        ];
    }

    /**
     * @return string
     * @throws Exception
     */
    public function save(): string
    {
        $models = RationingDeviceData::find()
            ->andWhere(['rationingDeviceId' => $this->rationingDeviceId])
            ->orderBy(['operationNumber' => SORT_ASC])
            ->all();
        $transaction = Yii::$app->db->beginTransaction();
        foreach ($models as $model) {
            $addModel = new PresentationBookDataDeviceRepair([
                'bookId' => $this->bookId,
                'rationingDeviceId' => $this->rationingDeviceId,
                'rationingDeviceDataId' => $model->id,
                'norma' => $model->countItems * $model->norma,
            ]);
            if (!$addModel->save()) {
                $transaction->rollBack();
                return implode(',', $addModel->getErrorSummary(true));
            }
        }
        $transaction->commit();

        return 'Данные успешно сохранены!';
    }
}
