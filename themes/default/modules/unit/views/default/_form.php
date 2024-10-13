<?php

declare(strict_types=1);

use app\common\rbac\CollectionRolls;
use app\modules\unit\models\Unit;
use app\modules\unit\widgets\UnitWidget;
use kilyanov\architect\interfaces\AnswerInterface;
use yii\bootstrap5\ActiveForm;

/**
 * @var $model Unit
 */

?>
<?php
$form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-md-4">
        <?= $form
            ->field($model, 'parentId')
            ->widget(
                UnitWidget::class,
                [
                    'options' => ['prompt' => ''],
                    'pluginOptions' => [
                        'dropdownParent' => '#' . AnswerInterface::MODAL_ID,
                    ]
                ]
            )
        ?>
    </div>
    <div class="col-md-4">
        <?= $form
            ->field($model, 'name') ?>
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
            ->field($model, 'description')->textarea()
        ?>
    </div>
</div>

<?php ActiveForm::end(); ?>
