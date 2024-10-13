<?php

declare(strict_types=1);

use app\modules\industry\models\PresentationBook;
use kilyanov\architect\interfaces\AnswerInterface;
use kilyanov\architect\widgets\ModalWidget;
use yii\bootstrap5\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\Url;
use yii\web\JsExpression;

/**
 * @var $model PresentationBook
 * @var $form ActiveForm
 */

?>

<div class="row">
    <div class="col-12">
        <label class="form-label"><?= $model->getAttributeLabel('orderRationingId') ?> </label>
        <?= Select2::widget([
            'model' => $model,
            'attribute' => 'orderRationingId',
            'language' => 'ru',
            'data' => $model->orderRationingId ? [$model->orderRationingId => $model->orderRationingRelation->name] : [],
            'options' => [
                'placeholder' => 'Выберите нормировку',
                'id' => 'order-rationing-id',
                'prompt' => ''
            ],
            'pluginOptions' => [
                'dropdownParent' => '#' . AnswerInterface::MODAL_ID,
                'allowClear' => true,
                'ajax' => [
                    'url' => Url::to(['/industry/order/rationing/list']),
                    'dataType' => 'json',
                    'data' => new JsExpression('function(params) {
                         var orderId = $(\'#order-id\').val();
                         if (orderId == "") {
                            alert("Выберите номер заказа.");
                         }
                         return {orderId:orderId, name:params.term};
                    }'),
                ],
            ],
        ]); ?>
    </div>
</div>

<div class="row" style="margin-top: 10px;">
    <div class="col-6">
        <?= $form
            ->field($model, 'orderRationingDataId')
            ->widget(Select2::class, [
                'model' => $model,
                'attribute' => 'orderRationingDataId',
                'language' => 'ru',
                'data' => [],
                'options' => [
                    'placeholder' => 'Выберите пункт нормировки',
                    'id' => 'order-rationing-data-id',
                    'prompt' => ''
                ],
                'pluginOptions' => [
                    'dropdownParent' => '#' . AnswerInterface::MODAL_ID,
                    'allowClear' => true,
                    'ajax' => [
                        'url' => Url::to(['/industry/order/rationing-data/list']),
                        'dataType' => 'json',
                        'data' => new JsExpression('function(params) {
                         var orderRationingId = $(\'#order-rationing-id\').val();
                         return {orderRationingId:orderRationingId, name:params.term};
                    }'),
                    ],
                ],
            ])
        ?>
    </div>
    <div class="col-2">
        <?= $form
            ->field($model, 'status')
            ->dropdownList(PresentationBook::getStatusList(), ['prompt' => '']) ?>
    </div>
    <div class="col-4">
        <?= $form
            ->field($model, 'checkClose')->checkbox() ?>
    </div>
</div>
