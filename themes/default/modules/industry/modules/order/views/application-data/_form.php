<?php

declare(strict_types=1);

use app\modules\application\models\ApplicationData;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use kilyanov\architect\interfaces\AnswerInterface;
use yii\bootstrap5\ActiveForm;
use yii\web\JsExpression;

/**
 * @var $model ApplicationData
 */

?>
<?php
$form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-md-4">
        <?= $form
            ->field($model, 'resourceId')->widget(
                Select2::class, [
                'data' => $model->resourceId === null ? [] : [$model->resourceId => $model->resourceRelation->getFullName()],
                'options' => ['multiple' => false, 'placeholder' => '', 'id' => 'resource-id'],
                'pluginOptions' => [
                    'dropdownParent' => '#' . AnswerInterface::MODAL_ID,
                    'allowClear' => true,
                    'minimumInputLength' => 2,
                    'language' => [
                        'errorLoading' => new JsExpression("function () { return 'Поиск...'; }"),
                    ],
                    'ajax' => [
                        'url' => ['/resource/default/list'],
                        'dataType' => 'json',
                        'data' => new JsExpression('function(params) { return {q:params.term}; }')
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(tags) { return tags.text; }'),
                    'templateSelection' => new JsExpression('function (tags) { return tags.text; }'),
                ],
            ]); ?>
    </div>
    <div class="col-md-2">
        <?= $form
            ->field($model, 'mark')
            ->dropDownList(ApplicationData::getMarkList(), ['prompt' => ''])
        ?>
    </div>
    <div class="col-md-4">
        <?= $form
            ->field($model, 'type')
            ->dropDownList(ApplicationData::getTypeList(), ['prompt' => ''])
        ?>
    </div>
    <div class="col-md-2">
        <?= $form->field($model, 'quantity')
        ?>
    </div>
</div>

<div class="row">
    <?=
    $form->field($model, 'deliveryTime', [
        'options' => ['class' => 'form-group col-md-3']])
        ->widget(
            DatePicker::class, [
            'options' => ['placeholder' => 'Дата...'],
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'dd.mm.yyyy'
            ]
        ]);
    ?>
    <div class="col-md-3">
        <?= $form->field($model, 'quantityReceipt')
        ?>
    </div>
    <?=
    $form->field($model, 'receiptDate', [
        'options' => ['class' => 'form-group col-md-3']])
        ->widget(
            DatePicker::class, [
            'options' => ['placeholder' => 'Дата...'],
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'dd.mm.yyyy'
            ]
        ]);
    ?>
    <div class="col-md-3">
        <?= $form->field($model, 'designation')
        ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <?= $form->field($model, 'comment')->textarea() ?>
    </div>
</div>
<?php ActiveForm::end(); ?>
