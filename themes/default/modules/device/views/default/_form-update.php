<?php

declare(strict_types=1);

use app\modules\device\models\Device;
use app\modules\device\models\DeviceGroup;
use kartik\select2\Select2;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;

/**
 * @var $model Device
 */
?>
<?php
$form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'deviceGroupId')->widget(Select2::class, [
            'data' => DeviceGroup::asDropDown(),
            'language' => 'ru',
            'options' => ['placeholder' => 'Выберите группу', 'id' => 'deviceGroupId'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
        ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'deviceTypeId')->widget(Select2::class, [
            'data' => $model->deviceTypeId ? [$model->deviceTypeId => $model->deviceTypeRelation->name] : [],
            'language' => 'ru',
            'options' => ['placeholder' => 'Выберите наименование', 'id' => 'deviceTypeId'],
            'pluginOptions' => [
                //'dropdownParent' => '#ajaxCrudModal',
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
        ]);
        ?>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <?= $form->field($model, 'deviceNameId')->widget(Select2::class, [
            'data' => $model->deviceNameId ? [$model->deviceNameId => $model->deviceNameRelation->name] : [],
            'language' => 'ru',
            'options' => ['placeholder' => 'Выберите тип', 'id' => 'deviceNameId'],
            'pluginOptions' => [
                //'dropdownParent' => '#ajaxCrudModal',
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
        ]);
        ?>
    </div>
    <div class="col-md-3">
        <?= $form->field($model, 'devicePropertyId')->widget(Select2::class, [
            'language' => 'ru',
            'data' => $model->devicePropertyId ? [$model->devicePropertyId => $model->devicePropertyRelation->name] : [],
            'options' => ['placeholder' => 'Характеристка', 'id' => 'devicePropertyId'],
            'pluginOptions' => [
                //'dropdownParent' => '#ajaxCrudModal',
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
        ]);
        ?>
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
<div class="row">
    <div class="col-md-12">
        <?= $form->field($model, 'linkView')->textInput(['disabled' => true]) ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <?= $form->field($model, 'linkBase')->textInput(['disabled' => true]) ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <?= $form->field($model, 'certificateNumber')->textInput(['disabled' => true]) ?>
    </div>
</div>
<div class="d-md-block">
    <?= Html::submitButton(
        'Сохранить',
        ['class' => 'btn btn-primary']
    ) ?>
    <?php if (!empty($model->deviceInfoVerificationRelation)): ?>
        <?= Html::a(
            'Ред. свед.',
            ['/device/device-info-verification/update', 'id' => $model->deviceInfoVerificationRelation->id],
            ['class' => 'btn btn-danger', 'role' => 'modal-remote']
        ) ?>
    <?php endif; ?>
    <?= Html::a(
        'Доб. свед.',
        ['/device/device-info-verification/create', 'deviceId' => $model->id],
        ['class' => 'btn btn-info', 'role' => 'modal-remote']
    ) ?>
</div>

<?php
ActiveForm::end(); ?>
