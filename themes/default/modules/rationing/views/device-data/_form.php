<?php

declare(strict_types=1);

use app\common\rbac\CollectionRolls;
use app\modules\personal\modules\special\widgets\PersonalSpecialWidget;
use app\modules\rationing\models\RationingDeviceData;
use app\modules\unit\widgets\UnitWidget;
use yii\bootstrap5\ActiveForm;

/** @var $model RationingDeviceData **/

?>
<?php
$form = ActiveForm::begin(); ?>

<div class="row">
    <div class="col-md-12">
        <?= $form
            ->field($model, 'operationNumber') ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <?= $form
            ->field($model, 'name')->textarea() ?>
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        <?= $form->field($model, 'unitId')->widget(UnitWidget::class)?>
    </div>
    <div class="col-md-3">
        <?= $form->field($model, 'specialId')->widget(PersonalSpecialWidget::class) ?>
    </div>
    <div class="col-md-3">
        <?= $form->field($model, 'ed') ?>
    </div>
    <div class="col-md-3">
        <?= $form->field($model, 'periodicity') ?>
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        <?= $form->field($model, 'countItems') ?>
    </div>
    <div class="col-md-3">
        <?= $form->field($model, 'category') ?>
    </div>
    <div class="col-md-3">
        <?= $form->field($model, 'norma') ?>
    </div>
    <?php if (Yii::$app->user->can(CollectionRolls::ROLE_ROOT)): ?>
        <div class="col-md-3">
            <?= $form
                ->field($model, 'hidden')
                ->dropDownList($model::getHiddenList(), ['prompt' => '']) ?>
        </div>
    <?php endif; ?>
</div>

<?php ActiveForm::end(); ?>
