<?php

declare(strict_types=1);

use app\common\rbac\CollectionRolls;
use app\modules\contract\widgets\ContractWidget;
use kilyanov\architect\interfaces\AnswerInterface;
use yii\bootstrap5\ActiveForm;
use app\modules\industry\models\OrderList;

/** @var $model OrderList **/


?>
<?php
$form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-md-2">
        <?= $form
            ->field($model, 'type')
            ->dropDownList(OrderList::getTypeList(), ['prompt' => '']) ?>
    </div>
    <div class="col-md-2">
        <?= $form
            ->field($model, 'numberScore') ?>
    </div>
    <div class="col-md-8">
        <?= $form
            ->field($model, 'contractId')
            ->widget(ContractWidget::class,['pluginOptions' => ['dropdownParent' => '#' . AnswerInterface::MODAL_ID]])?>
    </div>

</div>
<div class="row">
    <div class="col-md-3">
        <?= $form
            ->field($model, 'number') ?>
    </div>
    <div class="col-md-3">
        <?= $form
            ->field($model, 'year') ?>
    </div>
    <div class="col-md-3">
        <?= $form
            ->field($model, 'status')
            ->dropDownList(OrderList::getStatusList(), ['prompt' => '']) ?>
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
            ->field($model, 'description')->textarea() ?>
    </div>
</div>
<?php ActiveForm::end(); ?>
