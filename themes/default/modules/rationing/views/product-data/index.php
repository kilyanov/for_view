<?php

declare(strict_types=1);

use app\common\architect\LinkDeleteAll;
use app\common\grid\ActionColumn;
use app\common\grid\HiddenColumn;
use app\common\grid\MachineColumn;
use app\common\grid\PageSummaryDataColumn;
use app\common\grid\PersonalSpecialColumn;
use app\common\grid\UnitColumn;
use app\modules\rationing\factory\ActionMenuFactory;
use app\modules\rationing\models\RationingProductData;
use app\widgets\GroupButtonWidget;
use kilyanov\architect\widgets\ModalWidget;
use yii\bootstrap5\Html;
use yii\bootstrap5\LinkPager;
use yii\data\ActiveDataProvider;
use yii\grid\CheckboxColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var RationingProductData $model
 * @var ActiveDataProvider $dataProvider
 * @var string $forceReload
 * @var array $listAccess
 */

$this->title = $model->rationingRelation->name;
$this->params['breadcrumbs'][] = ['label' => 'Нормировки по ремонту ВВТ', 'url' => ['/rationing/product']];
$this->params['breadcrumbs'][] = $this->title;

?>

<?php Pjax::begin(['id' => $forceReload]); ?>

<?= GroupButtonWidget::widget([
    'access' => $listAccess,
    'configUrl' => ['rationingId' => $model->rationingId],
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
            'factory' => ActionMenuFactory::class,
        ],
        [
            'headerOptions' => ['style' => 'width:150px'],
            'class' => MachineColumn::class,
            'productId' => function ($model) {
                /** @var $model RationingProductData */
                return $model->rationingRelation->productId;
            },
        ],
        [
            'headerOptions' => ['style' => 'width:100px'],
            'attribute' => 'point',
        ],
        [
            'headerOptions' => ['style' => 'width:100px'],
            'attribute' => 'subItem',
        ],
        [
            'format' => 'raw',
            'attribute' => 'name',
            'value' => function ($model) {
                if ($model->checkList === 1) {
                    return Html::a($model->name,
                        [
                            '/rationing/product-military',
                            'rationingDataId' => $model->id,
                        ],
                        ['target' => '_blank', 'data-pjax' => 0]);
                } else {
                    return $model->name;
                }
            },
            'filter' => true
        ],
        [
            'headerOptions' => ['style' => 'width:100px'],
            'class' => UnitColumn::class,
            'value' => function ($model) {
                /** @var $model RationingProductData */
                return ($model->type == RationingProductData::TYPE_SUB_POINT) ?
                    $model->unitRelation->name : '';
            },
            'showParent' => true,
        ],
        [
            'headerOptions' => ['style' => 'width:70px'],
            'attribute' => 'ed',
            'filter' => false
        ],
        [
            'headerOptions' => ['style' => 'width:70px'],
            'attribute' => 'countItems',
            'filter' => false
        ],
        [
            'headerOptions' => ['style' => 'width:70px'],
            'attribute' => 'periodicity',
            'filter' => false
        ],
        [
            'headerOptions' => ['style' => 'width:70px'],
            'attribute' => 'specialId',
            'class' => PersonalSpecialColumn::class,
        ],
        [
            'headerOptions' => ['style' => 'width:70px'],
            'attribute' => 'category',
            'filter' => false
        ],
        [
            'headerOptions' => ['style' => 'width:70px'],
            'format' => ['decimal', 2],
            'attribute' => 'norma',
            'filter' => false
        ],
        [
            'headerOptions' => ['style' => 'width:70px'],
            'class' => PageSummaryDataColumn::class,
            'format' => ['decimal', 2],
            'attribute' => 'normaAll',
            'filter' => false
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
