<?php

declare(strict_types=1);

use app\common\rbac\CollectionRolls;
use app\modules\resource\models\Resource;
use yii\bootstrap5\ActiveForm;

/**
 * @var $model Resource
 */

/**
 * @var $model Resource
 */

?>
<?php
$form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-md-6">
        <?= $form
            ->field($model, 'name') ?>
    </div>
    <div class="col-md-6">
        <?= $form
            ->field($model, 'mark') ?>
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        <?= $form
            ->field($model, 'ed') ?>
    </div>
    <div class="col-md-3">
        <?= $form
            ->field($model, 'stamp') ?>
    </div>
    <div class="col-md-3">
        <?= $form
            ->field($model, 'size') ?>
    </div>
    <?php if (Yii::$app->user->can(CollectionRolls::ROLE_ROOT)): ?>
        <div class="col-md-3">
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
