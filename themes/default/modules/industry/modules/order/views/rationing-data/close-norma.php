<?php

declare(strict_types=1);

use app\modules\industry\models\OrderRationingDataClose;
use yii\bootstrap5\ActiveForm;

/** @var $model OrderRationingDataClose **/

?>
<?php
$form = ActiveForm::begin(); ?>

<div class="row">
    <div class="col-md-4">
        <?= $form
            ->field($model, 'norma') ?>
    </div>
    <div class="col-md-4">
        <?= $form
            ->field($model, 'year') ?>
    </div>
    <div class="col-md-4">
        <?= $form
            ->field($model, 'month')
            ->dropDownList($model->getMonthList(), ['prompt' => '']) ?>
    </div>
    <?= $form
        ->field($model, 'orderRationingDataId')->hiddenInput()->label(false) ?>
</div>

<?php ActiveForm::end(); ?>
