<?php

declare(strict_types=1);

use app\common\rbac\CollectionRolls;
use kartik\date\DatePicker;
use yii\bootstrap5\ActiveForm;
use app\modules\device\models\DeviceRejection;

/**
 * @var $model DeviceRejection
 */


?>
<?php
$form = ActiveForm::begin(); ?>
<?= $form->field($model, 'deviceId')->hiddenInput()->label(false); ?>
<div class="row">
    <?=
    $form->field($model, 'rejection_date', [
        'options' => ['class' => 'form-group col-md-4']])
        ->widget(
            DatePicker::class, [
            'options' => ['placeholder' => 'Дата забраковки...'],
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'dd.mm.yyyy'
            ]
        ]);
    ?>
    <?php if (!$model->isNewRecord): ?>
        <div class="col-md-4">
            <?= $form
                ->field($model, 'status')
                ->dropdownList($model::getStatusList()) ?>
        </div>
    <?php endif; ?>
    <?php if (Yii::$app->user->can(CollectionRolls::ROLE_ROOT)): ?>
        <div class="col-md-4">
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
