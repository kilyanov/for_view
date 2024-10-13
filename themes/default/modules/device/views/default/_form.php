<?php

declare(strict_types=1);

use app\modules\device\models\Device;
use app\modules\device\models\DeviceGroup;
use kartik\select2\Select2;
use kilyanov\architect\interfaces\AnswerInterface;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;
use yii\web\JsExpression;

/** @var $model Device **/

?>
<?php
$form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-md-6">
        <?= $form
            ->field($model, 'deviceGroupId')
            ->dropdownList(DeviceGroup::find()->asDropDown(), ['options' => ['id' => 'deviceGroupId']]) ?>
    </div>

    <div class="col-md-6">
        <label><?= $model->getAttributeLabel('deviceTypeId') ?> </label>
        <?= Select2::widget([
            'model' => $model,
            'attribute' => 'deviceTypeId',
            'language' => 'ru',
            'data' => $model->deviceTypeId ? [$model->deviceTypeId => $model->deviceTypeRelation->name] : [],
            'options' => ['placeholder' => 'Выберите наименование', 'id' => 'deviceTypeId'],
            'pluginOptions' => [
                'dropdownParent' => '#' . AnswerInterface::MODAL_ID,
                'minimumInputLength' => 1,
                'allowClear' => true,
                'ajax' => [
                    'url' => Url::to(['/device/type/default/list']),
                    'dataType' => 'json',
                    'data' => new JsExpression('function(params) {
                            return {name:params.term};
                    }'),
                ],
            ],
        ]); ?>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <label><?= $model->getAttributeLabel('deviceNameId') ?> </label>
        <?= Select2::widget([
            'model' => $model,
            'attribute' => 'deviceNameId',
            'language' => 'ru',
            'data' => $model->deviceNameId ? [$model->deviceNameId => $model->deviceNameRelation->name] : [],
            'options' => ['placeholder' => 'Выберите тип', 'id' => 'deviceNameId'],
            'pluginOptions' => [
                'dropdownParent' => '#' . AnswerInterface::MODAL_ID,
                'minimumInputLength' => 1,
                'allowClear' => true,
                'ajax' => [
                    'url' => Url::to(['/device/name/default/list']),
                    'dataType' => 'json',
                    'data' => new JsExpression(
                        'function(params) {
                        var deviceTypeId = $(\'#deviceTypeId\').val();
                        return {
                            deviceTypeId:deviceTypeId,
                            name:params.term
                        };
                    }'),
                ],
            ],
        ]); ?>
    </div>
    <div class="col-md-3">
        <label><?= $model->getAttributeLabel('devicePropertyId') ?> </label>
        <?= Select2::widget([
            'model' => $model,
            'attribute' => 'devicePropertyId',
            'language' => 'ru',
            'data' => $model->devicePropertyId ? [$model->devicePropertyId => $model->devicePropertyRelation->name] : [],
            'options' => ['placeholder' => 'Характеристка', 'id' => 'devicePropertyId'],
            'pluginOptions' => [
                'dropdownParent' => '#' . AnswerInterface::MODAL_ID,
                'minimumInputLength' => 1,
                'allowClear' => true,
                'ajax' => [
                    'url' => Url::to(['/device/property/default/list']),
                    'dataType' => 'json',
                    'data' => new JsExpression(
                        'function(params) {
                        var deviceNameId = $(\'#deviceNameId\').val();
                        return {
                            deviceNameId:deviceNameId,
                            name:params.term
                        };
                    }')
                ],
            ],
        ]); ?>
    </div>
    <div class="col-md-2">
        <?= $form
            ->field($model, 'factoryNumber') ?>
    </div>
    <div class="col-md-3">
        <?= $form
            ->field($model, 'status')
            ->dropdownList(Device::getStatusList()) ?>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <?= $form
            ->field($model, 'inventoryNumber') ?>
    </div>
    <div class="col-md-4">
        <?= $form
            ->field($model, 'stateRegister') ?>
    </div>
    <div class="col-md-4">
        <?= $form
            ->field($model, 'verificationPeriod')->dropdownList(Device::getVerificationPeriodList()) ?>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <?= $form
            ->field($model, 'norma') ?>
    </div>
    <div class="col-md-4">
        <?= $form
            ->field($model, 'category') ?>
    </div>
    <div class="col-md-4">
        <?= $form
            ->field($model, 'yearRelease') ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <?= $form
            ->field($model, 'description')->textarea()
        ?>
    </div>
</div>

<?php
ActiveForm::end(); ?>
