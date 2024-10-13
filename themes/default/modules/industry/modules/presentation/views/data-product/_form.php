<?php

declare(strict_types=1);

use app\modules\industry\models\PresentationBookDataProduct;
use yii\bootstrap5\ActiveForm;

/**
 * @var $model PresentationBookDataProduct
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
