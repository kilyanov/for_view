<?php

declare(strict_types=1);

use app\modules\personal\modules\special\widgets\PersonalSpecialWidget;
use app\modules\unit\widgets\UnitWidget;
use kartik\select2\Select2;
use kilyanov\architect\interfaces\AnswerInterface;
use yii\bootstrap5\ActiveForm;
use yii\web\JsExpression;
use app\modules\personal\models\Personal;

/** @var $model Personal **/

?>
<?php
$form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-md-3">
        <?= $form
            ->field($model, 'unitId')
            ->widget(
                UnitWidget::class,
                [
                    'showParent' => true,
                    'options' => [
                        'id' => 'unit-id',
                        'prompt' => ''
                    ]
                ]
            )
        ?>
    </div>
    <div class="col-md-3">
        <?= $form
            ->field($model, 'groupId')->widget(
                Select2::class, [
                'data' => $model->groupId === null ? [] :
                    [$model->groupId => $model->groupRelation->getFullName()],
                'options' => [
                    'multiple' => false,
                    'placeholder' => '', 'id' => 'group-id',
                    'prompt' => ''
                ],
                'pluginOptions' => [
                    'dropdownParent' => '#' . AnswerInterface::MODAL_ID,
                    'allowClear' => true,
                    'language' => [
                        'errorLoading' => new JsExpression("function () { return 'Поиск...'; }"),
                    ],
                    'ajax' => [
                        'url' => ['/personal/group/default/list'],
                        'dataType' => 'json',
                        'data' => new JsExpression('function(params) {
                                    var unitId = $(\'#unit-id\').val();
                                    return {unitId:unitId};
                            }'),
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(tags) { return tags.text; }'),
                    'templateSelection' => new JsExpression('function (tags) { return tags.text; }'),
                ],
            ]); ?>
    </div>
    <div class="col-md-3">
        <?= $form
            ->field($model, 'specialId')
            ->widget(
                PersonalSpecialWidget::class,
                [
                    'options' => [
                        'placeholder' => '',
                        'prompt' => ''
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ]
                ]
            )
        ?>
    </div>
    <div class="col-md-3">
        <?= $form
            ->field($model, 'discharge') ?>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <?= $form
            ->field($model, 'fistName') ?>
    </div>
    <div class="col-md-4">
        <?= $form
            ->field($model, 'lastName') ?>
    </div>
    <div class="col-md-4">
        <?= $form
            ->field($model, 'secondName') ?>
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        <?= $form
            ->field($model, 'ratio') ?>
    </div>
    <div class="col-md-3">
        <?= $form
            ->field($model, 'salary') ?>
    </div>
    <div class="col-md-3">
        <?= $form
            ->field($model, 'typeSalary')
            ->dropDownList(Personal::getTypeSalaryList(), ['prompt' => ''])?>
    </div>
    <div class="col-md-3">
        <?= $form
            ->field($model, 'status')
            ->dropDownList(Personal::getStatusList(), ['prompt' => ''])?>
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        <?= $form
            ->field($model, 'type')
            ->dropDownList(Personal::getTypeList(), ['prompt' => ''])?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <?= $form
            ->field($model, 'description')->textarea() ?>
    </div>
</div>
<?php
ActiveForm::end(); ?>
