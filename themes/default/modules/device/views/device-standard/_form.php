<?php

declare(strict_types=1);

use app\modules\device\models\DeviceStandard;
use yii\bootstrap5\ActiveForm;

/**
 * @var $model DeviceStandard
 */

?>
<?php
$form = ActiveForm::begin(); ?>
<?= $form->field($model, 'deviceId')->hiddenInput()->label(false); ?>
    <div class="row">
        <div class="col-md-6">
            <?=
            $form->field($model, 'numberStandard');
            ?>
        </div>
    </div>
<?php ActiveForm::end(); ?>
