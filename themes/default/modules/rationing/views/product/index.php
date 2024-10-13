<?php

declare(strict_types=1);

use app\common\architect\LinkDeleteAll;
use app\common\grid\ActionColumn;
use app\common\grid\HiddenColumn;
use app\common\grid\ImpactColumn;
use app\common\grid\ProductColumn;
use app\common\grid\UnitColumn;
use app\widgets\GroupButtonWidget;
use kilyanov\architect\widgets\ModalWidget;
use yii\bootstrap5\Html;
use yii\bootstrap5\LinkPager;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\grid\CheckboxColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var ActiveQuery $model
 * @var ActiveDataProvider $dataProvider
 * @var string $forceReload
 * @var array $listAccess
 */

$this->title = 'Нормировки по ремонту ВВТ';

$this->params['breadcrumbs'][] = $this->title;

?>

<?php Pjax::begin(['id' => $forceReload]); ?>

<?= GroupButtonWidget::widget([
    'access' => $listAccess,
    'visible' => ['move' => false, 'export' => false, 'copy' => true],
]) ?>

<?= GridView::widget([
    'filterModel' => $model,
    'dataProvider' => $dataProvider,
    'tableOptions' => Yii::$app->params['gridViewTableOptions'],
    'columns' => [
        [
            'headerOptions' => ['style' => 'width:20px'],
            'class' => CheckboxColumn::class,
        ],
        [
            'class' => ActionColumn::class,
        ],
        [
            'class' => ProductColumn::class
        ],
        [
            'headerOptions' => ['style' => 'width:100px'],
            'class' => UnitColumn::class,
            'showParent' => true
        ],
        [
            'headerOptions' => ['style' => 'width:100px'],
            'class' => ImpactColumn::class
        ],
        [
            'format' => 'raw',
            'attribute' => 'name',
            'value' => function ($model) {
                return Html::a(
                    $model->name,
                    ['/rationing/product-data/index', 'rationingId' => $model->id],
                    ['data-pjax' => 0]
                );
            }
        ],
        [
            'headerOptions' => ['style' => 'width:100px'],
            'format' => ['decimal', 2],
            'attribute' => 'norma'
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
