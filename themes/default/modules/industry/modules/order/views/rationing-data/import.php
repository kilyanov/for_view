<?php

declare(strict_types=1);

use kilyanov\architect\models\BaseImportModel;
use yii\bootstrap5\ActiveForm;

/** @var $model BaseImportModel **/

?>
<?php
$form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-md-12">
        <?= $form->field($model, 'file')->fileInput() ?>
    </div>
<?php ActiveForm::end(); ?>
