<?php

declare(strict_types=1);

use app\common\grid\MonthColumn;
use app\common\grid\PageSummaryDataColumn;
use app\modules\industry\models\OrderRationingDataClose;
use yii\bootstrap5\LinkPager;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var OrderRationingDataClose $model
 * @var ActiveDataProvider $dataProvider
 * @var string $forceReload
 */

?>

<?php Pjax::begin(['id' => $forceReload]); ?>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'showFooter' => true,
    'tableOptions' => Yii::$app->params['gridViewTableOptions'],
    'columns' => [
        [
            'header' => 'Пункт',
            'attribute' => 'orderRationingDataId',
            'value' => function ($model) {
                return $model->getfullName();
            },
            'filter' => false,
        ],
        [
            'attribute' => 'year',
        ],
        [
            'class' => MonthColumn::class
        ],
        [
            'class' => PageSummaryDataColumn::class,
            'attribute' => 'norma',
            'filter' => false,
        ],
    ],
    'pager' => [
        'class' => LinkPager::class,
        'options' => ['style' => 'margin-left:10px;']
    ]
]);
?>
<?php Pjax::end(); ?>
