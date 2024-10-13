<?php

declare(strict_types=1);

use app\modules\industry\models\PresentationBookDataDeviceRepair;
use yii\bootstrap5\ActiveForm;

/**
 * @var $model PresentationBookDataDeviceRepair
 * @var $form ActiveForm
 */

?>
<?php
$form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-md-4">
        <?= $form->field($model, 'norma'); ?>
    </div>
</div>
<?php ActiveForm::end(); ?>
