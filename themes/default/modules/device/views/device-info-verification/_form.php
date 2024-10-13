<?php

declare(strict_types=1);

use app\modules\device\models\DeviceInfoVerification;
use yii\bootstrap5\ActiveForm;

/**
 * @var $model DeviceInfoVerification
 */

?>
<?php
$form = ActiveForm::begin(); ?>
<?= $form->field($model, 'deviceId')->hiddenInput()->label(false) ?>
<div class="row">
    <div class="col-md-12">
        <?= $form->field($model, 'linkView') ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <?= $form->field($model, 'linkBase') ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <?= $form->field($model, 'certificateNumber') ?>
    </div>
</div>
<?php
ActiveForm::end(); ?>
