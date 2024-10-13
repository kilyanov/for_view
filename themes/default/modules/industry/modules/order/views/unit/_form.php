<?php

declare(strict_types=1);

use app\common\rbac\CollectionRolls;
use app\modules\industry\models\OrderToUnit;
use app\modules\unit\models\Unit;
use yii\bootstrap5\ActiveForm;

/** @var $model OrderToUnit **/

?>
<?php
$form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-md-4">
        <?= $form
            ->field($model, 'unitId')
            ->dropDownList(Unit::asDropDown(['parent' => true]), ['prompt' => '']) ?>
    </div>

    <?php if (Yii::$app->user->can(CollectionRolls::ROLE_ROOT)): ?>
        <div class="col-md-4">
            <?= $form
                ->field($model, 'hidden')
                ->dropDownList($model::getHiddenList(), ['prompt' => '']) ?>
        </div>
    <?php endif; ?>
</div>

<?php ActiveForm::end(); ?>
