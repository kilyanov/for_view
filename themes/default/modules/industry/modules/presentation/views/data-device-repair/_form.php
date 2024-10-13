<?php

declare(strict_types=1);

use app\modules\industry\modules\presentation\forms\PresentBookDataDeviceRepairForm;
use kilyanov\architect\interfaces\AnswerInterface;
use yii\bootstrap5\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\Url;
use yii\web\JsExpression;

/**
 * @var $model PresentBookDataDeviceRepairForm
 * @var $form ActiveForm
 */

?>
<?php
$form = ActiveForm::begin(); ?>
<?= $form->field($model, 'bookId')->hiddenInput()->label(false); ?>
<div class="row">
    <div class="col-10">
        <label><?= $model->getAttributeLabel('rationingDeviceId') ?> </label>
        <?= Select2::widget([
            'model' => $model,
            'attribute' => 'rationingDeviceId',
            'language' => 'ru',
            'data' => [],
            'options' => ['placeholder' => 'Выберите СИ', 'id' => 'rationing-device-id'],
            'pluginOptions' => [
                'dropdownParent' => '#' . AnswerInterface::MODAL_ID,
                'allowClear' => true,
                'ajax' => [
                    'url' => Url::to(['/rationing/device/list']),
                    'dataType' => 'json',
                    'data' => new JsExpression('function(params) {
                         return {name:params.term};
                    }'),
                ],
            ],
        ]); ?>
    </div>
</div>
<?php ActiveForm::end(); ?>
