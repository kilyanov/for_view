<?php

declare(strict_types=1);

use app\common\rbac\CollectionRolls;
use kartik\date\DatePicker;
use yii\bootstrap5\ActiveForm;
use app\modules\device\models\DeviceVerification;

/**
 * @var $model DeviceVerification
 */

?>
<?php
$form = ActiveForm::begin(); ?>
<?= $form->field($model, 'deviceId')->hiddenInput()->label(false); ?>
<div class="row">
    <?=
    $form->field($model, 'verification_date', [
        'options' => ['class' => 'form-group col-md-3']])
        ->widget(
            DatePicker::class, [
            'options' => ['placeholder' => 'Дата поверки...'],
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'dd.mm.yyyy'
            ]
        ]);
    ?>
    <?=
    $form->field($model, 'nextVerification_date', [
        'options' => ['class' => 'form-group col-md-3']])
        ->widget(
            DatePicker::class, [
            'options' => ['placeholder' => 'Дата следующей поверки'],
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'dd.mm.yyyy'
            ]
        ]);
    ?>
    <?php if (!$model->isNewRecord): ?>
        <div class="col-md-3">
            <?= $form
                ->field($model, 'status')
                ->dropdownList($model::getStatusList()) ?>
        </div>
    <?php endif; ?>
    <?php if (Yii::$app->user->can(CollectionRolls::ROLE_ROOT)): ?>
        <div class="col-md-3">
            <?= $form
                ->field($model, 'hidden')
                ->dropDownList($model::getHiddenList(), ['prompt' => '']) ?>
        </div>
    <?php endif; ?>
</div>
<?php ActiveForm::end(); ?>
