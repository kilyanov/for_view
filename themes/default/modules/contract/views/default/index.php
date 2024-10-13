<?php

declare(strict_types=1);

use app\common\architect\LinkDeleteAll;
use app\common\grid\ActionColumn;
use app\common\grid\HiddenColumn;
use app\common\grid\StatusColumn;
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

$this->title = 'Контракты';

$this->params['breadcrumbs'][] = $this->title;

?>

<?= GroupButtonWidget::widget([
    'access' => $listAccess,
    'visible' => ['move' => false,]
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
            'format' => 'raw',
            'attribute' => 'number',
            'value' => function ($model) {
                return Html::a(
                    $model->number,
                    '/contract/specification/index/' . $model->id,
                    ['data-pjax' => 0]
                );
            }
        ],
        'name',
        'description',
        [
            'attribute' => 'dateFinding',
            'filter' => false,
        ],
        [
            'attribute' => 'validityPeriod',
            'filter' => false,
        ],
        [
            'headerOptions' => ['style' => 'width:180px'],
            'class' => StatusColumn::class,
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
