<?php

declare(strict_types=1);

use app\common\rbac\CollectionRolls;
use app\modules\product\models\Product;
use kartik\select2\Select2;
use kilyanov\architect\interfaces\AnswerInterface;
use yii\bootstrap5\ActiveForm;
use app\modules\industry\models\RepairProduct;
use yii\web\JsExpression;

/** @var $model RepairProduct **/

?>
<?php
$form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-md-4">
        <?= $form
            ->field($model, 'productId')
            ->dropDownList(Product::asDropDown(), ['prompt' => '', 'id' => 'product-id',]) ?>
    </div>
    <div class="col-md-4">
        <?= $form
            ->field($model, 'productNodeId')->widget(
                Select2::class, [
                'data' => $model->productNodeId === null ? [] :
                    [$model->productNodeId => $model->productNodeRelation->getFullName()],
                'options' => [
                    'multiple' => false,
                    'placeholder' => '', 'id' => 'product-node-id',
                    'prompt' => ''
                ],
                'pluginOptions' => [
                    'dropdownParent' => '#' . AnswerInterface::MODAL_ID,
                    'allowClear' => true,
                    'language' => [
                        'errorLoading' => new JsExpression("function () { return 'Поиск...'; }"),
                    ],
                    'ajax' => [
                        'url' => ['/product/node/list'],
                        'dataType' => 'json',
                        'data' => new JsExpression('function(params) {
                                    var productId = $(\'#product-id\').val();
                                    return {productId:productId, name:params.term};
                        }'),
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(tags) { return tags.text; }'),
                    'templateSelection' => new JsExpression('function (tags) { return tags.text; }'),
                ],
            ]); ?>
    </div>
    <div class="col-md-4">
        <?= $form
            ->field($model, 'productBlockId')->widget(
                Select2::class, [
                'data' => $model->productBlockId === null ? [] :
                    [$model->productBlockId => $model->productBlockRelation->getFullName()],
                'options' => [
                    'multiple' => false,
                    'placeholder' => '', 'id' => 'product-block-id',
                    'prompt' => ''
                ],
                'pluginOptions' => [
                    'dropdownParent' => '#' . AnswerInterface::MODAL_ID,
                    'allowClear' => true,
                    'language' => [
                        'errorLoading' => new JsExpression("function () { return 'Поиск...'; }"),
                    ],
                    'ajax' => [
                        'url' => ['/product/block/list'],
                        'dataType' => 'json',
                        'data' => new JsExpression('function(params) {
                                    var productId = $(\'#product-id\').val();
                                    var productNodeId = $(\'#product-node-id\').val();
                                    return {productId:productId, productNodeId:productNodeId, name:params.term};
                        }'),
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(tags) { return tags.text; }'),
                    'templateSelection' => new JsExpression('function (tags) { return tags.text; }'),
                ],
            ]); ?>
    </div>
</div>
<div class="row">
    <div class="col-md-<?= (Yii::$app->user->can(CollectionRolls::ROLE_ROOT)) ? '6' : '12' ?>">
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
            ->field($model, 'comment')->textarea()
        ?>
    </div>
</div>

<?php ActiveForm::end(); ?>
