<?php

declare(strict_types=1);

use app\modules\device\forms\FilterDeviceForm;
use kartik\date\DatePicker;
use kartik\daterange\DateRangePicker;
use yii\bootstrap5\ActiveForm;

/** @var $model FilterDeviceForm **/

?>
<?php
$form = ActiveForm::begin(); ?>
<div class="row">
    <h4>Текущая поверка</h4>
    <div class="col-md-4">
        <?=
        $form->field($model, 'date')
            ->widget(
                DatePicker::class, [
                'options' => ['placeholder' => 'Дата поверки'],
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'dd.mm.yyyy'
                ]
            ]);
        ?>
    </div>
    <div class="col-md-4">
        <?= $form
            ->field($model, 'datePeriod')->widget(
                DateRangePicker::class,
                [
                    'options' => ['placeholder' => 'Период поверки'],
                    'pluginOptions' => [
                        'format' => 'Y-m-d',
                    ]
                ]
            ) ?>
    </div>
</div>
<div class="row">
    <h4>Следующая поверка</h4>
    <div class="col-md-4">
        <?=
        $form->field($model, 'dateNext')
            ->widget(
                DatePicker::class, [
                'options' => ['placeholder' => 'Дата поверки'],
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'dd.mm.yyyy'
                ]
            ]);
        ?>
    </div>
    <div class="col-md-4">
        <?= $form
            ->field($model, 'datePeriodNext')->widget(
                DateRangePicker::class,
                [
                    'options' => ['placeholder' => 'Период поверки'],
                    'pluginOptions' => [
                        'format' => 'Y-m-d',
                    ]
                ]
            ) ?>
    </div>
</div>
<?php
ActiveForm::end(); ?>
