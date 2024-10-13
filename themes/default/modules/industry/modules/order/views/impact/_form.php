<?php

declare(strict_types=1);

use app\common\rbac\CollectionRolls;
use app\modules\impact\models\Impact;
use app\modules\industry\models\OrderToImpact;
use yii\bootstrap5\ActiveForm;

/** @var $model OrderToImpact **/

?>
<?php
$form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-md-4">
        <?= $form
            ->field($model, 'impactId')
            ->dropDownList(Impact::asDropDown(), ['prompt' => '']) ?>
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
