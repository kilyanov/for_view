<?php

declare(strict_types=1);

use app\common\grid\NormaCloseForOrderColumn;
use app\common\grid\StatusColumn;
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

$this->title = 'Списание Н/Ч по заказам';

$this->params['breadcrumbs'][] = $this->title;

?>

<?= WriteOffNormaButton::widget(['active' => 'order']) ?>

<?php Pjax::begin(['id' => $forceReload]); ?>
<?= GridView::widget([
    'filterModel' => $model,
    'dataProvider' => $dataProvider,
    'showFooter' => true,
    'tableOptions' => Yii::$app->params['gridViewTableOptions'],
    'columns' => [
        [
            'attribute' => 'number'
        ],
        [
            'class' => NormaCloseForOrderColumn::class,
            'format' => ['decimal', 2],
            'header' => 'Н/Ч',
        ],
        [
            'class' => StatusColumn::class,
        ],
    ],
    'pager' => [
        'class' => LinkPager::class,
        'options' => ['style' => 'margin-left:10px;']
    ]
]);
?>
<?php Pjax::end(); ?>

