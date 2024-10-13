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
            ->field($model, 'year') ?>
    </div>
    <div class="col-md-4">
        <?= $form
            ->field($model, 'month')
            ->dropDownList($model->getMonthList(), ['prompt' => '']) ?>
    </div>
</div>
<?php if(count($model->orderRationingDataId) > 0): ?>
    <?php foreach ($model->orderRationingDataId as $item): ?>
        <?= $form->field($model, 'orderRationingDataId[]')->hiddenInput(['value' => $item])->label(false); ?>
    <?php endforeach; ?>
<?php endif; ?>

<?php ActiveForm::end(); ?>
