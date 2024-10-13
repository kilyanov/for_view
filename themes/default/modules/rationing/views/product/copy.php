<?php

declare(strict_types=1);

use app\common\rbac\CollectionRolls;
use app\modules\impact\widgets\ImpactWidget;
use app\modules\product\widgets\ProductWidget;
use app\modules\rationing\forms\RationingProductForm;
use app\modules\rationing\widgets\RationingProductWidget;
use app\modules\unit\widgets\UnitWidget;
use kilyanov\architect\interfaces\AnswerInterface;
use yii\bootstrap5\ActiveForm;

/** @var $model RationingProductForm * */

?>

<?php
$form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-md-4">
        <?= $form
            ->field($model, 'rationingId')
            ->widget(
                RationingProductWidget::class,
                ['pluginOptions' => ['dropdownParent' => '#' . AnswerInterface::MODAL_ID,]]
            ) ?>
    </div>
    <div class="col-md-4">
        <?= $form
            ->field($model, 'unitId')
            ->widget(UnitWidget::class, ['pluginOptions' => ['dropdownParent' => '#' . AnswerInterface::MODAL_ID,]]) ?>
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
    <div class="col-md-8">
        <?= $form->field($model, 'name') ?>
    </div>
    <div class="col-md-4">
        <?= $form
            ->field($model, 'productId')
            ->widget(ProductWidget::class, ['pluginOptions' => ['dropdownParent' => '#' . AnswerInterface::MODAL_ID,]]) ?>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <?= $form
            ->field($model, 'impactId')
            ->widget(ImpactWidget::class, ['pluginOptions' => ['dropdownParent' => '#' . AnswerInterface::MODAL_ID,]]) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>
