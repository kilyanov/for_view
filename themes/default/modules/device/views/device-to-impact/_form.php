<?php

declare(strict_types=1);

use app\common\rbac\CollectionRolls;
use app\modules\device\models\DeviceToImpact;
use app\modules\impact\widgets\ImpactWidget;
use yii\bootstrap5\ActiveForm;

/**
 * @var $model DeviceToImpact
 */

?>
<?php
$form = ActiveForm::begin(); ?>
<?= $form->field($model, 'deviceId')->hiddenInput()->label(false); ?>
<div class="row">
    <div class="col-md-4">
        <?= $form
            ->field($model, 'impactId')
            ->widget(
                ImpactWidget::class,
                [
                    'options' => [
                        'placeholder' => 'Выберите вид воздействия',
                    ],
                ]
            ) ?>
    </div>
    <?php if (!$model->isNewRecord): ?>
        <div class="col-md-4">
            <?= $form
                ->field($model, 'status')
                ->dropdownList(DeviceToImpact::getStatusList()) ?>
        </div>
    <?php endif; ?>
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
            ->field($model, 'description')->textarea() ?>
    </div>
</div>
<?php ActiveForm::end(); ?>
