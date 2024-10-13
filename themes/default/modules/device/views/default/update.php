<?php

declare(strict_types=1);

use app\modules\device\models\Device;
use kilyanov\architect\widgets\ModalWidget;
use yii\bootstrap5\Html;
use yii\widgets\DetailView;
use yii\widgets\Pjax;

/**
 * @var $model Device
 * @var string $forceReload
 */

$this->title = 'Информация по СИ';

$this->params['breadcrumbs'][] = $this->title;

?>
<div class="container-fluid" style="margin-top: 10px;">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Общая информация по СИ</h5>
                </div>
                <div class="card-body">
                    <?php Pjax::begin(['id' => $forceReload]) ?>
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'id',
                            [
                                'label' => 'Группа',
                                'value' => $model->deviceGroupRelation->name,
                            ],
                            [
                                'label' => 'Подразделение',
                                'value' => $model->deviceToUnitRelation?->unitRelation->name,
                            ],
                            [
                                'label' => 'Воздействия',
                                'value' => $model->deviceToImpactRelation?->impactRelation->getFullName(),
                            ],
                            [
                                'label' => 'Тип',
                                'value' => $model->deviceTypeRelation?->name,
                            ],
                            [
                                'label' => 'Наименование',
                                'value' => $model->deviceNameRelation?->name,
                            ],
                            [
                                'label' => 'Тех. характ.',
                                'value' => $model->devicePropertyRelation?->name,
                            ],
                            [
                                'attribute' => 'stateRegister',
                                'value' => $model->stateRegister === null ? '' : $model->stateRegister,
                            ],
                            'factoryNumber',
                            'inventoryNumber',
                            [
                                'label' => 'Период',
                                'value' => $model->getVerificationPeriod(),
                            ],
                            'norma',
                            'category',
                            [
                                'label' => 'Статус',
                                'value' => $model->getStatus(),
                            ],
                            [
                                'attribute' => 'description',
                                'value' => $model->description,
                            ],
                            'yearRelease',
                            [
                                'format' => 'raw',
                                'label' => 'Номер эталона',
                                'value' => $model->deviceStandardRelation === null ?
                                    Html::a(
                                        'Создать',
                                        ['/device/device-standard/create', 'deviceId' => $model->id],
                                        ['class' => 'btn btn-primary', 'role' => 'modal-remote']
                                    ) :
                                    Html::a(
                                        $model->deviceStandardRelation?->numberStandard,
                                        ['/device/device-standard/update', 'id' => $model->deviceStandardRelation->id],
                                        ['class' => 'btn btn-primary', 'role' => 'modal-remote']
                                    )
                            ],
                            [
                                'attribute' => 'certificateNumber',
                                'value' => empty($model->certificateNumber) ? '' : $model->certificateNumber,
                            ],
                            [
                                'format' => 'raw',
                                'attribute' => 'linkView',
                                'value' => empty($model->linkView) ? '' :
                                    Html::a($model->linkView, $model->linkView, ['target' => '_blank']),
                            ],
                            [
                                'format' => 'raw',
                                'attribute' => 'linkBase',
                                'value' => empty($model->linkBase) ? '' :
                                    Html::a($model->linkBase, $model->linkBase, ['target' => '_blank']),
                            ],
                        ],
                    ]);
                    ?>
                    <?php Pjax::end(); ?>
                </div>
            </div>
            <?= Yii::$app->runAction('/device/device-to-unit/index') ?>
            <?= Yii::$app->runAction('/device/device-to-impact/index') ?>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Редактировать информацию по СИ</h5>
                </div>
                <div class="card-body">
                    <?= $this->render('_form-update', ['model' => $model]); ?>
                </div>
            </div>
            <?= Yii::$app->runAction('/device/device-verification/index') ?>
            <?= Yii::$app->runAction('/device/device-rejection/index') ?>
        </div>
    </div>
</div>
<?= ModalWidget::widget() ?>
