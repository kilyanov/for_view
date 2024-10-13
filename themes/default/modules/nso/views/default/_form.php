<?php

declare(strict_types=1);

use app\common\rbac\CollectionRolls;
use app\modules\nso\models\Stand;
use app\modules\unit\widgets\UnitWidget;
use kartik\date\DatePicker;
use yii\bootstrap5\ActiveForm;

/** @var $model Stand * */

?>
<?php
$form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-md-4">
        <?= $form
            ->field($model, 'unitId')->widget(UnitWidget::class) ?>
    </div>
    <div class="col-md-4">
        <?= $form
            ->field($model, 'number') ?>
    </div>
    <div class="col-md-4">
        <?= $form
            ->field($model, 'inventoryNumber') ?>
    </div>

</div>
<div class="row">
    <div class="col-md-12">
        <?= $form
            ->field($model, 'name') ?>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <?= $form
            ->field($model, 'mark') ?>
    </div>
    <div class="col-md-4">
        <?= $form
            ->field($model, 'conservation')
            ->dropDownList([0 => 'Нет', 1 => 'Да'], ['prompt' => ''])
        ?>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <?= $form
            ->field($model, 'standardHours') ?>
    </div>
    <div class="col-md-4">
        <?= $form
            ->field($model, 'category') ?>
    </div>
    <?=
    $form->field($model, 'dateVerifications', [
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
            ->field($model, 'description')->textarea() ?>
    </div>
</div>
<?php ActiveForm::end(); ?>
