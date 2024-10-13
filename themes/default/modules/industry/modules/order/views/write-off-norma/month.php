<?php

declare(strict_types=1);

use app\common\grid\MonthColumn;
use app\common\grid\PageSummaryDataColumn;
use app\modules\industry\models\OrderList;
use app\modules\industry\modules\order\widgets\WriteOffNormaButton;
use yii\bootstrap5\LinkPager;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var OrderList $model
 * @var ActiveDataProvider $dataProvider
 * @var string $forceReload
 */

$this->title = 'Списание Н/Ч по месяцам';

$this->params['breadcrumbs'][] = $this->title;

?>

<?= WriteOffNormaButton::widget(['active' => 'month']) ?>

<?php Pjax::begin(['id' => $forceReload]); ?>
<?= GridView::widget([
    'filterModel' => $model,
    'dataProvider' => $dataProvider,
    'showFooter' => true,
    'tableOptions' => Yii::$app->params['gridViewTableOptions'],
    'columns' => [
        [
            'attribute' => 'number_order',
            'value' => function ($model) {
                return $model->number_order;
            }
        ],
        [
            'class' => PageSummaryDataColumn::class,
            'attribute' => 'sumHour',
            'format' => ['decimal', 2],
            'filter' => false,
        ],
        [
            'class' => MonthColumn::class
        ],
        [
            'attribute' => 'year'
        ],
    ],
    'pager' => [
        'class' => LinkPager::class,
        'options' => ['style' => 'margin-left:10px;']
    ]
]);
?>
<?php Pjax::end(); ?>
