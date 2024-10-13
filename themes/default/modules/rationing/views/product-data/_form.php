<?php

declare(strict_types=1);

use app\common\rbac\CollectionRolls;
use app\modules\industry\modules\machine\widgets\MachineWidget;
use app\modules\personal\modules\special\widgets\PersonalSpecialWidget;
use app\modules\rationing\models\RationingProductData;
use app\modules\unit\widgets\UnitWidget;
use kilyanov\architect\interfaces\AnswerInterface;
use yii\bootstrap5\ActiveForm;

/** @var $model RationingProductData **/

?>
<?php
$form = ActiveForm::begin(); ?>

<div class="row">
    <div class="col-md-3">
        <?= $form
            ->field($model, 'type')
            ->dropDownList(RationingProductData::getTypeList(), ['prompt' => '']) ?>
    </div>
    <div class="col-md-3">
        <?= $form
            ->field($model, 'point') ?>
    </div>
    <div class="col-md-3">
        <?= $form
            ->field($model, 'subItem') ?>
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
        <?= $form->field($model, 'name') ?>
    </div>
</div>

<div class="row">
    <div class="col-md-2">
        <?= $form
            ->field($model, 'unitId')
            ->widget(
                UnitWidget::class,
                [
                    'showParent' => true,
                    'options' => ['prompt' => ''],
                    'pluginOptions' => ['dropdownParent' => '#' . AnswerInterface::MODAL_ID,]
                ]
            )?>
    </div>
    <div class="col-md-10">
        <?= $form
            ->field($model, 'machineId')
            ->widget(MachineWidget::class,
                [
                    'productId' => $model->rationingRelation?->productId,
                    'pluginOptions' => ['dropdownParent' => '#' . AnswerInterface::MODAL_ID,]
                ]
            )
        ?>
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        <?= $form
            ->field($model, 'ed') ?>
    </div>
    <div class="col-md-3">
        <?= $form
            ->field($model, 'countItems') ?>
    </div>
    <div class="col-md-3">
        <?= $form
            ->field($model, 'periodicity') ?>
    </div>
    <div class="col-md-3">
        <?= $form
            ->field($model, 'category') ?>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <?= $form
            ->field($model, 'specialId')
            ->widget(
                PersonalSpecialWidget::class,
                [
                    'options' => ['prompt' => '']
                ]
            ) ?>
    </div>
    <div class="col-md-3">
        <?= $form
            ->field($model, 'norma') ?>
    </div>
    <div class="col-md-3">
        <?= $form
            ->field($model, 'checkList')
            ->dropDownList([0 => 'Нет', 1 => 'Да'], ['prompt' => '']) ?>
    </div>
    <div class="col-md-3">
        <?= $form
            ->field($model, 'sort') ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <?= $form
            ->field($model, 'comment')->textarea() ?>
    </div>
</div>

<?php ActiveForm::end(); ?>
