<?php

declare(strict_types=1);

use app\common\rbac\CollectionRolls;
use kartik\select2\Select2;
use kilyanov\architect\interfaces\AnswerInterface;
use yii\bootstrap5\ActiveForm;

use app\modules\device\models\DeviceName;
use yii\web\JsExpression;

/**
 * @var $model DeviceName
 */

?>
<?php
$form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-md-3">
        <?= $form
            ->field($model, 'deviceTypeId')->widget(
                Select2::class, [
                'data' => $model->deviceTypeId === null ? [] :
                    [$model->deviceTypeId => $model->deviceTypeRelation->getFullName()],
                'options' => ['multiple' => false, 'placeholder' => '', 'id' => 'type-device-id'],
                'pluginOptions' => [
                    'dropdownParent' => '#' . AnswerInterface::MODAL_ID,
                    'allowClear' => true,
                    'language' => [
                        'errorLoading' => new JsExpression("function () { return 'Поиск...'; }"),
                    ],
                    'ajax' => [
                        'url' => ['/device/type/default/list'],
                        'dataType' => 'json',
                        'data' => new JsExpression('function(params) {
                                return {name:params.term};
                            }'),
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(tags) { return tags.text; }'),
                    'templateSelection' => new JsExpression('function (tags) { return tags.text; }'),
                ],
            ]); ?>
    </div>
    <div class="col-md-3">
        <?= $form
            ->field($model, 'name') ?>
    </div>
    <div class="col-md-3">
        <?= $form
            ->field($model, 'status')
            ->dropDownList($model::getStatusList(), ['prompt' => '']) ?>
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
            ->field($model, 'description')->textarea()
        ?>
    </div>
</div>

<?php ActiveForm::end(); ?>
