<?php

declare(strict_types=1);

use app\common\rbac\CollectionRolls;
use app\modules\industry\models\Machine;
use app\modules\product\models\Product;
use yii\bootstrap5\ActiveForm;

/** @var $model Machine **/

?>
<?php
$form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-md-4">
        <?= $form
            ->field($model, 'productId')
            ->dropDownList(Product::find()->asDropDown(), ['prompt' => '']) ?>
    </div>
    <div class="col-md-4">
        <?= $form
            ->field($model, 'number') ?>
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
            ->field($model, 'name')
        ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <?= $form
            ->field($model, 'comment')->textarea()
        ?>
    </div>
</div>

<?php ActiveForm::end(); ?>
