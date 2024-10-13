<?php

declare(strict_types=1);

use app\common\architect\LinkDeleteAll;
use app\common\grid\ActionColumn;
use app\common\grid\HiddenColumn;
use app\common\grid\UnitColumn;
use app\modules\industry\models\OrderToUnit;
use app\modules\industry\modules\order\widgets\GroupButtonWidget as OrderGroupButtonWidget;
use app\widgets\GroupButtonWidget;
use kilyanov\architect\widgets\ModalWidget;
use yii\bootstrap5\LinkPager;
use yii\data\ActiveDataProvider;
use yii\grid\CheckboxColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var OrderToUnit $model
 * @var ActiveDataProvider $dataProvider
 * @var string $forceReload
 * @var array $listAccess
 */

$this->title = 'Подразделения по заказу' . $model->orderRelation->number;

$this->params['breadcrumbs'][] = ['label' => 'Заказы', 'url' => ['/industry/order']];
$this->params['breadcrumbs'][] = $this->title;

?>

<?= OrderGroupButtonWidget::widget([
    'configUrl' => ['orderId' => $model->orderId],
    'active' => 'unit',
]) ?>
<div class="clearfix" style="margin-top: 7px;"></div>

<?= GroupButtonWidget::widget([
    'access' => $listAccess,
    'configUrl' => ['orderId' => $model->orderId],
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
            'class' => UnitColumn::class,
            'showParent' => true,
            'multiple' => false,
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
