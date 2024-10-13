<?php

declare(strict_types=1);

use app\common\rbac\CollectionRolls;
use app\modules\nso\models\StandChart;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use kilyanov\architect\interfaces\AnswerInterface;
use yii\bootstrap5\ActiveForm;
use yii\web\JsExpression;

/** @var $model StandChart * */

?>
<?php
$form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-md-12">
        <?= $form
            ->field($model, 'standId')->widget(
                Select2::class, [
                'data' => $model->standId === null ? [] : [$model->standId => $model->standRelation->getFullName()],
                'options' => ['multiple' => false, 'placeholder' => '', 'id' => 'standId'],
                'pluginOptions' => [
                    'dropdownParent' =>  '#' . AnswerInterface::MODAL_ID,
                    'allowClear' => true,
                    'minimumInputLength' => 1,
                    'language' => [
                        'errorLoading' => new JsExpression("function () { return 'Поиск...'; }"),
                    ],
                    'ajax' => [
                        'url' => ['/nso/default/list'],
                        'dataType' => 'json',
                        'data' => new JsExpression('function(params) { return {q:params.term}; }')
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
            ->field($model, 'monthPlan')
            ->dropDownList(StandChart::getMonthList(), ['prompt' => '']) ?>
    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'year'); ?>
    </div>
    <?=
    $form->field($model, 'dateFact',[
        'options' => ['class' => 'form-group col-md-6']])
        ->widget(
            DatePicker::class, [
            'options' => ['placeholder' => 'Дата...'],
            'pluginOptions' => [
                'autoclose'=>true,
                'format' => 'dd.mm.yyyy'
            ]
        ]);
    ?>
</div>
<?php if (Yii::$app->user->can(CollectionRolls::ROLE_ROOT)): ?>
    <div class="row">
        <div class="col-md-4">
            <?= $form
                ->field($model, 'hidden')
                ->dropDownList($model::getHiddenList(), ['prompt' => '']) ?>
        </div>
    </div>
<?php endif; ?>
<div class="row">
    <div class="col-md-12">
        <?= $form
            ->field($model, 'comment')->textarea() ?>
    </div>
</div>
<?php ActiveForm::end(); ?>
