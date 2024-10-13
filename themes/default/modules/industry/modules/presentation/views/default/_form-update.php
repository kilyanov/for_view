<?php

declare(strict_types=1);

use app\modules\impact\widgets\ImpactWidget;
use app\modules\industry\models\PresentationBook;
use app\modules\personal\widgets\PersonalListWidget;
use kilyanov\architect\interfaces\AnswerInterface;
use yii\bootstrap5\ActiveForm;

/**
 * @var $model PresentationBook
 */

?>
<?php
$form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'name'); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'norma'); ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'number'); ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'inventoryNumber'); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'personalId')
                ->widget(PersonalListWidget::class, [
                    'groupId' => $model->groupId,
                    'options' => ['id' => 'personal-id'],
                    'pluginOptions' => ['dropdownParent' => '#' . AnswerInterface::MODAL_ID,]
                ]); ?>
        </div>
        <div class="col-md-2">
            <?= $form
                ->field($model, 'status')
                ->dropdownList(PresentationBook::getStatusList()) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'impactId')
                ->widget(ImpactWidget::class, [
                    'options' => ['id' => 'impact-id'],
                    'pluginOptions' => ['dropdownParent' => '#' . AnswerInterface::MODAL_ID,]
                ]); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'comment')->textarea(); ?>
        </div>
    </div>
<?php ActiveForm::end(); ?>
