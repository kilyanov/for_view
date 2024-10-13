<?php

declare(strict_types=1);

use app\modules\application\models\Application;
use app\modules\industry\modules\order\widgets\OrderListWidget;
use app\modules\unit\widgets\UnitWidget;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use kilyanov\architect\interfaces\AnswerInterface;
use yii\bootstrap5\ActiveForm;
use yii\web\JsExpression;

/**
 * @var $model Application
 */

?>
<?php
$form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-md-4">
        <?= $form
            ->field($model, 'orderId')
            ->widget(
                OrderListWidget::class,
                [
                    'pluginOptions' => [
                        'dropdownParent' => '#' . AnswerInterface::MODAL_ID,
                        'allowClear' => true,
                    ],
                    'options' => [
                        'id' => 'orderId',
                        'prompt' => ''
                    ]
                ]
            )
        ?>
    </div>
    <div class="col-md-8">
        <?= $form
            ->field($model, 'productId')->widget(
                Select2::class, [
                'data' => $model->productId === null ? [] :
                    [
                        $model->productId => $model->productRelation->getFullName()
                    ],
                'options' => ['multiple' => false, 'placeholder' => '', 'id' => 'group-id'],
                'pluginOptions' => [
                    'dropdownParent' => '#' . AnswerInterface::MODAL_ID,
                    'allowClear' => true,
                    'language' => [
                        'errorLoading' => new JsExpression("function () { return 'Поиск...'; }"),
                    ],
                    'ajax' => [
                        'url' => ['/industry/order/product/list'],
                        'dataType' => 'json',
                        'data' => new JsExpression('function(params) {
                                    var orderId = $(\'#orderId\').val();
                                    return {orderId:orderId, number:params.term};
                            }'),
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(tags) { return tags.text; }'),
                    'templateSelection' => new JsExpression('function (tags) { return tags.text; }'),
                ],
            ]); ?>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <?= $form
            ->field($model, 'unitId')->widget(UnitWidget::class)
        ?>
    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'number')
        ?>
    </div>
    <?=
    $form->field($model, 'dateFiling', [
        'options' => ['class' => 'form-group col-md-4']])
        ->widget(
            DatePicker::class, [
            'options' => ['placeholder' => 'Дата...'],
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'dd.mm.yyyy'
            ]
        ]);
    ?>
</div>
<div class="row">
    <div class="col-md-12">
        <?= $form->field($model, 'comment')->textarea() ?>
    </div>
</div>
<?php ActiveForm::end(); ?>
