<?php

declare(strict_types=1);

use app\modules\industry\models\PresentationBook;
use kilyanov\architect\interfaces\AnswerInterface;
use yii\bootstrap5\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\Url;
use yii\web\JsExpression;

/**
 * @var $model PresentationBook
 * @var $form ActiveForm
 */

?>

<div class="row">
    <?= $form->field($model, 'unitId')->hiddenInput(['value' => Yii::$app->user->identity->unitId])->label(false) ?>
    <div class="col-12">
        <?= $form
            ->field($model, 'standId')
            ->widget(Select2::class, [
                'model' => $model,
                'attribute' => 'standId',
                'language' => 'ru',
                'data' => $model->standId ? [$model->standId => $model->standRelation->getFullNameWithUnit()] : [],
                'options' => ['placeholder' => 'Выберите НСО', 'id' => 'stand-id'],
                'pluginOptions' => [
                    'dropdownParent' => '#' . AnswerInterface::MODAL_ID,
                    'allowClear' => true,
                    'ajax' => [
                        'url' => Url::to(['/nso/default/list']),
                        'dataType' => 'json',
                        'data' => new JsExpression('function(params) {
                         return {q:params.term};
                    }'),
                    ],
                ],
            ])
        ?>
    </div>
</div>
<div class="row">
    <div class="col-2">
        <?= $form
            ->field($model, 'status')
            ->dropdownList(PresentationBook::getStatusList()) ?>
    </div>
    <div class="col-2">
        <?= $form->field($model, 'norma')?>
    </div>
</div>
