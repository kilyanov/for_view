<?php

declare(strict_types=1);

use app\common\architect\LinkDeleteAll;
use app\common\grid\ActionColumn;
use app\common\grid\HiddenColumn;
use app\common\grid\ProductBlockColumn;
use app\common\grid\ProductColumn;
use app\common\grid\ProductNodeColumn;
use app\widgets\GroupButtonWidget;
use kilyanov\architect\widgets\ModalWidget;
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

$this->title = 'Ремонтируемы изделия';

$this->params['breadcrumbs'][] = $this->title;

?>

<?= GroupButtonWidget::widget([
    'access' => $listAccess,
    'visible' => ['export' => false, 'import' => true]
]) ?>

<?php Pjax::begin(['id' => $forceReload]); ?>
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
            'class' => ProductColumn::class,
            'multiple' => false,
            'headerOptions' => ['style' => 'width:100px'],
        ],
        [
            'class' => ProductNodeColumn::class,
        ],
        [
            'class' => ProductBlockColumn::class,
        ],
        [
            'attribute' => 'number',
            'headerOptions' => ['style' => 'width:100px;']
        ],
        'comment',
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
