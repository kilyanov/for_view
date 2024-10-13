<?php

declare(strict_types=1);

use app\common\architect\LinkDeleteAll;
use app\common\grid\ActionColumn;
use app\common\grid\ContractColumn;
use app\common\grid\HiddenColumn;
use app\common\grid\OrderTypeColumn;
use app\common\grid\StatusColumn;
use app\modules\industry\models\OrderList;
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

$this->title = 'Заказы';

$this->params['breadcrumbs'][] = $this->title;

?>
<?php Pjax::begin(['id' => $forceReload]); ?>

<?= GroupButtonWidget::widget([
    'access' => $listAccess,
]) ?>

<?= GridView::widget([
    'filterModel' => $model,
    'dataProvider' => $dataProvider,
    'rowOptions' => function ($model) {
        /** @var OrderList $model */
        return $model->getColorCell();
    },
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
            'headerOptions' => ['style' => 'width:140px'],
            'class' => OrderTypeColumn::class,
        ],
        [
            'headerOptions' => ['style' => 'width:140px'],
            'format' => 'raw',
            'attribute' => 'number',
            'value' => function ($model) {
                return Html::a(
                    $model->number,
                    ['/industry/order/default/view', 'orderId' => $model->id],
                    ['data-pjax' => 0, 'class' => 'btn btn-light']
                );
            }
        ],
        [
            'class' => ContractColumn::class,
        ],
        [
            'headerOptions' => ['style' => 'width:140px'],
            'attribute' => 'year',
        ],
        [
            'headerOptions' => ['style' => 'width:140px'],
            'class' => StatusColumn::class,
        ],
        [
            'headerOptions' => ['style' => 'width:140px'],
            'attribute' => 'numberScore',
        ],
        'description',
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
