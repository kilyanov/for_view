<?php

declare(strict_types=1);

use app\modules\industry\modules\order\forms\NewNumberItemForm;
use yii\bootstrap5\ActiveForm;

/** @var $model NewNumberItemForm **/

?>
<?php
$form = ActiveForm::begin(); ?>

<div class="row">
    <div class="col-md-12">
        <?= $form
            ->field($model, 'number') ?>
    </div>
</div>
<?php if(count($model->orderRationingDataId) > 0): ?>
    <?php foreach ($model->orderRationingDataId as $item): ?>
        <?= $form->field($model, 'orderRationingDataId[]')->hiddenInput(['value' => $item])->label(false); ?>
    <?php endforeach; ?>
<?php endif; ?>

<?php ActiveForm::end(); ?>
