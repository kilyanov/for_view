<?php

declare(strict_types=1);

use app\common\rbac\CollectionRolls;
use kartik\select2\Select2;
use kilyanov\architect\interfaces\AnswerInterface;
use yii\bootstrap5\ActiveForm;
use yii\web\JsExpression;
use app\modules\industry\models\OrderToProduct;

/** @var $model OrderToProduct **/

?>
<?php
$form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-md-4">
        <?= $form
            ->field($model, 'productId')->widget(
                Select2::class, [
                'data' => $model->productId === null ? [] : [$model->productId => $model->getFullName()],
                'options' => ['multiple' => false, 'placeholder' => '', 'id' => 'product-id'],
                'pluginOptions' => [
                    'dropdownParent' => '#' . AnswerInterface::MODAL_ID,
                    'allowClear' => true,
                    'minimumInputLength' => 2,
                    'language' => [
                        'errorLoading' => new JsExpression("function () { return 'Поиск...'; }"),
                    ],
                    'ajax' => [
                        'url' => ['/industry/product/default/list'],
                        'dataType' => 'json',
                        'data' => new JsExpression(
                            '
                                function(params) {
                                 return {number:params.term};
                            }'
                        ),
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(tags) { return tags.text; }'),
                    'templateSelection' => new JsExpression('function (tags) { return tags.text; }'),
                ],
            ]); ?>
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
            ->field($model, 'comment')->textarea()
        ?>
    </div>
</div>

<?php ActiveForm::end(); ?>
