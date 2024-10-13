<?php

declare(strict_types=1);

use app\common\rbac\CollectionRolls;
use app\modules\contract\models\Contract;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use kilyanov\architect\interfaces\AnswerInterface;
use yii\bootstrap5\ActiveForm;
use yii\web\JsExpression;

/**
 * @var $model Contract
 */

?>
<?php
$form = ActiveForm::begin(); ?>

<div class="row">
    <?=
    $form->field($model, 'dateFinding', [
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
    <?=
    $form->field($model, 'validityPeriod', [
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
    <div class="col-md-4">
        <?= $form
            ->field($model, 'status')
            ->dropDownList(Contract::getStatusList(), ['prompt' => '']) ?>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <?= $form
            ->field($model, 'institutionId')->widget(
                Select2::class, [
                'data' => $model->institutionId === null ? [] : [$model->institutionId => $model->institutionRelation->name],
                'options' => ['multiple' => false, 'placeholder' => '', 'id' => 'entity-id'],
                'pluginOptions' => [
                    'dropdownParent' => '#' . AnswerInterface::MODAL_ID,
                    'allowClear' => true,
                    'language' => [
                        'errorLoading' => new JsExpression("function () { return 'Поиск...'; }"),
                    ],
                    'ajax' => [
                        'url' => ['/institution/default/list'],
                        'dataType' => 'json',
                        'data' => new JsExpression('function(params) { return {name:params.term}; }'),
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(tags) { return tags.text; }'),
                    'templateSelection' => new JsExpression('function (tags) { return tags.text; }'),
                ],
            ]); ?>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <?= $form
            ->field($model, 'name') ?>
    </div>
    <div class="col-md-<?= Yii::$app->user->can(CollectionRolls::ROLE_ROOT) ? '3' : '6' ?>">
        <?= $form
            ->field($model, 'number') ?>
    </div>
    <?php if (Yii::$app->user->can(CollectionRolls::ROLE_ROOT)): ?>
        <div class="col-md-3">
            <?= $form
                ->field($model, 'hidden')
                ->dropDownList($model::getHiddenList(), ['prompt' => '']) ?>
        </div>
    <?php endif; ?>
</div>
<div class="row">
    <div class="col-md-12">
        <?= $form
            ->field($model, 'description')->textarea() ?>
    </div>
</div>
<?php ActiveForm::end(); ?>
