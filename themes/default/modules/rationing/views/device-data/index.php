<?php

declare(strict_types=1);

use app\common\architect\LinkDeleteAll;
use app\common\grid\ActionColumn;
use app\common\grid\HiddenColumn;
use app\common\grid\PageSummaryDataColumn;
use app\common\grid\PersonalSpecialColumn;
use app\common\grid\UnitColumn;
use app\modules\rationing\models\RationingDeviceData;
use app\widgets\GroupButtonWidget;
use kilyanov\architect\widgets\ModalWidget;
use yii\bootstrap5\LinkPager;
use yii\data\ActiveDataProvider;
use yii\grid\CheckboxColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var RationingDeviceData $model
 * @var ActiveDataProvider $dataProvider
 * @var string $forceReload
 * @var array $listAccess
 */

$this->title = 'Перечень работ на ' . $model->rationingDeviceRelation->name;

$this->params['breadcrumbs'][] = ['label' => 'Нормировки по ремонту СИ', 'url' => ['/rationing/device']];
$this->params['breadcrumbs'][] = $this->title;

?>

<?php Pjax::begin(['id' => $forceReload]); ?>

<?= GroupButtonWidget::widget([
    'access' => $listAccess,
    'visible' => ['export' => false],
    'configUrl' => ['rationingId' => $model->rationingDeviceId],
]) ?>

<?= GridView::widget([
    'filterModel' => $model,
    'dataProvider' => $dataProvider,
    'showFooter' => true,
    'tableOptions' => Yii::$app->params['gridViewTableOptions'],
    'columns' => [
        [
            'headerOptions' => ['style' => 'width:20px'],
            'class' => CheckboxColumn::class,
        ],
        [
            'class' => ActionColumn::class,
        ],[
            'headerOptions' => ['style' => 'width:100px'],
            'attribute' => 'operationNumber',
        ],
        [
            'attribute' => 'name',
        ],
        [
            'headerOptions' => ['style' => 'width:100px'],
            'class' => UnitColumn::class
        ],
        [
            'headerOptions' => ['style' => 'width:100px'],
            'class' => PersonalSpecialColumn::class
        ],
        [
            'headerOptions' => ['style' => 'width:100px'],
            'attribute' => 'ed',
        ],
        [
            'headerOptions' => ['style' => 'width:100px'],
            'attribute' => 'countItems',
        ],
        [
            'headerOptions' => ['style' => 'width:100px'],
            'attribute' => 'periodicity',
        ],
        [
            'headerOptions' => ['style' => 'width:100px'],
            'attribute' => 'category',
        ],
        [
            'headerOptions' => ['style' => 'width:100px'],
            'format' => ['decimal', 2],
            'attribute' => 'norma'
        ],
        [
            'headerOptions' => ['style' => 'width:100px'],
            'class' => PageSummaryDataColumn::class,
            'format' => ['decimal', 2],
            'attribute' => 'normaAll',
            'filter' => false,
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
