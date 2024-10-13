<?php

declare(strict_types=1);

use app\modules\impact\widgets\ImpactWidget;
use app\modules\industry\models\PresentationBook;
use app\modules\industry\modules\order\widgets\OrderListWidget;
use app\modules\personal\widgets\PersonalListWidget;
use kartik\date\DatePicker;
use kilyanov\architect\interfaces\AnswerInterface;
use yii\bootstrap5\ActiveForm;

/**
 * @var $model PresentationBook
 */

?>
<?php
$form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-md-4">
        <?= $form->field($model, 'orderId')
            ->widget(OrderListWidget::class, [
                'options' => ['id' => 'order-id', 'prompt' => ''],
                'pluginOptions' => ['dropdownParent' => '#' . AnswerInterface::MODAL_ID,]
            ]); ?>
    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'impactId')
            ->widget(ImpactWidget::class, [
                'options' => ['id' => 'impact-id', 'prompt' => ''],
                'pluginOptions' => ['dropdownParent' => '#' . AnswerInterface::MODAL_ID,]
            ]); ?>
    </div>
        <?=
    $form->field($model, 'date', [
        'options' => ['class' => 'form-group col-md-4']])
        ->widget(
            DatePicker::class, [
            'options' => ['placeholder' => 'Дата...'],
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'dd.mm.yyyy'
            ]
        ]); ?>
</div>

<?= $this->render($model->getTemplate(), ['form' => $form, 'model' => $model]); ?>

<div class="row">
    <div class="col-md-4">
        <?= $form->field($model, 'personalId')
            ->widget(PersonalListWidget::class, [
                'groupId' => $model->groupId,
                'options' => ['id' => 'personal-id', 'prompt' => ''],
                'pluginOptions' => ['dropdownParent' => '#' . AnswerInterface::MODAL_ID,]
            ]); ?>
    </div>
    <div class="col-md-8">
        <?= $form->field($model, 'name'); ?>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <?= $form->field($model, 'comment')->textarea(); ?>
    </div>
</div>

<?php ActiveForm::end(); ?>
