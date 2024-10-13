<?php

declare(strict_types=1);

use app\common\architect\LinkDeleteAll;
use app\common\grid\ActionColumn;
use app\common\grid\HiddenColumn;
use app\common\grid\MonthColumn;
use app\common\grid\PageSummaryDataColumn;
use app\common\grid\UnitColumn;
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

$this->title = 'График НСО';

$this->params['breadcrumbs'][] = $this->title;

?>

<?php Pjax::begin(['id' => $forceReload]); ?>
<?= GroupButtonWidget::widget([
    'access' => $listAccess,
    'visible' => ['move' => false]
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
            'header' => 'Подразделение',
            'class' => UnitColumn::class,
            'relation' => 'standRelation.unitRelation',
            'showParent' => true,
        ],
        [
            'header' => 'Инв. №',
            'attribute' => 'inventoryNumber',
            'value' => function ($model) {
                $inventoryNumber = $model->standRelation->inventoryNumber;
                return !empty($inventoryNumber) ? $inventoryNumber : '';
            },
            'filter' => true
        ],
        [
            'header' => 'Номер',
            'attribute' => 'number',
            'value' => function ($model) {
                $number = $model->standRelation->number;
                return !empty($number) ? $number : '';
            },
            'filter' => true
        ],
        [
            'attribute' => 'standId',
            'value' => function ($model) {
                return $model->getFullName();
            },
            'filter' => true
        ],
        [
            'class' => PageSummaryDataColumn::class,
            'header' => 'Н/ч',
            'attribute' => 'standardHours',
            'format' => 'decimal',
            'value' => function ($model) {
                return $model->standRelation->standardHours;
            },
            'filter' => false
        ],
        'year',
        [
            'attribute' => 'monthPlan',
            'class' => MonthColumn::class
        ],
        [
            'attribute' => 'monthFact',
            'class' => MonthColumn::class
        ],
        'dateFact',
        'conservation:boolean',
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
