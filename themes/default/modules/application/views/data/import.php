<?php

declare(strict_types=1);

use app\modules\application\models\ApplicationData;
use kilyanov\architect\models\BaseImportModel;
use yii\bootstrap5\ActiveForm;

/** @var $model BaseImportModel **/

?>
<?php
$form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-md-3">
        <?= $form
            ->field($model, 'mark')
            ->dropDownList(ApplicationData::getMarkList(), ['prompt' => ''])
        ?>
    </div>
    <div class="col-md-3">
        <?= $form
            ->field($model, 'type')
            ->dropDownList(ApplicationData::getTypeList(), ['prompt' => ''])
        ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'file')->fileInput() ?>
    </div>
<?php ActiveForm::end(); ?>
