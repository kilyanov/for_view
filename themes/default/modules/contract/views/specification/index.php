<?php

declare(strict_types=1);

use app\common\architect\LinkDeleteAll;
use app\common\grid\ActionColumn;
use app\common\grid\HiddenColumn;
use app\common\grid\ProductBlockColumn;
use app\common\grid\ProductColumn;
use app\common\grid\ProductNodeColumn;
use app\modules\contract\models\ContractSpecification;
use app\widgets\GroupButtonWidget;
use kilyanov\architect\widgets\ModalWidget;
use yii\bootstrap5\LinkPager;
use yii\data\ActiveDataProvider;
use yii\grid\CheckboxColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var ContractSpecification $model
 * @var ActiveDataProvider $dataProvider
 * @var string $forceReload
 * @var array $listAccess
 */

$this->title = 'Спецификация к контракту ' . $model->contractRelation->getFullName();
$this->params['breadcrumbs'][] = ['label' => 'Контракты', 'url' => ['/contract/default/index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<?= GroupButtonWidget::widget([
    'access' => $listAccess,
    'visible' => ['move' => false],
    'configUrl' => ['contractId' => $model->contractId]
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
        ],
        [
            'class' => ProductNodeColumn::class,
        ],
        [
            'class' => ProductBlockColumn::class,
        ],
        'factoryNumber',
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
