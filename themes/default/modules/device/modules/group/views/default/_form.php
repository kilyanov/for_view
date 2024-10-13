<?php

declare(strict_types=1);

use app\common\rbac\CollectionRolls;
use yii\bootstrap5\ActiveForm;
use app\modules\device\models\DeviceGroup;

/**
 * @var $model DeviceGroup
 */

?>
<?php
$form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-md-8">
        <?= $form
            ->field($model, 'name') ?>
    </div>
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
            ->field($model, 'description')->textarea()
        ?>
    </div>
</div>

<?php ActiveForm::end(); ?>
