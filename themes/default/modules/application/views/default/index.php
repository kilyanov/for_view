<?php

declare(strict_types=1);

use app\common\architect\LinkDeleteAll;
use app\common\grid\ActionColumn;
use app\common\grid\HiddenColumn;
use app\common\grid\OrderColumn;
use app\common\grid\PercentApplicationColumn;
use app\common\grid\ProductApplicationColumn;
use app\common\grid\UnitColumn;
use app\modules\application\factory\ActionMenuFactory;
use app\modules\contract\widgets\ContractWidget;
use app\widgets\GroupButtonWidget;
use kilyanov\architect\widgets\ModalWidget;
use yii\bootstrap5\Html;
use yii\bootstrap5\LinkPager;
use yii\data\ActiveDataProvider;
use yii\grid\CheckboxColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use app\modules\application\models\Application;

/**
 * @var Application $model
 * @var ActiveDataProvider $dataProvider
 * @var string $forceReload
 * @var array $listAccess
 */

$this->title = 'Заявки';

$this->params['breadcrumbs'][] = $this->title;

?>

<?php Pjax::begin(['id' => $forceReload]); ?>
<div class="card-header border-0">
    <?= GroupButtonWidget::widget([
        'access' => $listAccess,
        'visible' => ['move' => false],
        'configUrl' => Yii::$app->request->getQueryParams(),
    ]) ?>
    <div class="application-search" style="margin-top: 10px;">
        <?php $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
        ]); ?>

        <div class="col-6">
            <?= $form->field($model, 'contractId')
                ->widget(ContractWidget::class, [
                    'options' => ['placeholder' => 'Контракт']
                ]) ?>

            <?= Html::submitButton('Поиск', ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
<?= GridView::widget([
    'filterModel' => $model,
    'dataProvider' => $dataProvider,
    'tableOptions' => Yii::$app->params['gridViewTableOptions'],
    'columns' => [
        [
            'class' => CheckboxColumn::class,
            'headerOptions' => ['style' => 'width:20px'],
        ],
        [
            'class' => ActionColumn::class,
            'factory' => ActionMenuFactory::class,
        ],
        [
            'class' => OrderColumn::class,
        ],
        [
            'class' => UnitColumn::class,
            'showParent' => true,
        ],
        [
            'format' => 'raw',
            'attribute' => 'number',
            'value' => function ($model) {
                return Html::a($model->number,
                    ['/application/data/index', 'applicationId' => $model->id],
                    ['data-pjax' => 0]
                );
            },
            'filter' => true
        ],
        [
            'attribute' => 'dateFiling',
        ],
        [
            'class' => ProductApplicationColumn::class,
        ],
        [
            'attribute' => 'percent',
            'format' => ['decimal', 2],
            'header' => 'Процент обеспечения',
            'class' => PercentApplicationColumn::class
        ],
        [
            'attribute' => 'comment',
        ],
        [
            'class' => HiddenColumn::class,
        ],
    ],
    'pager' => [
        'class' => LinkPager::class,
        'options' => ['style' => 'margin-left:10px;']
    ]
]);
?>

<?php Pjax::end(); ?>

<?= (new LinkDeleteAll())->setAccess($listAccess)->make(); ?>

<?= ModalWidget::widget() ?>
