<?php

declare(strict_types=1);

use app\common\grid\MonthColumn;
use app\modules\industry\models\OrderRationingDataClose;
use app\modules\industry\modules\order\widgets\WriteOffNormaButton;
use yii\bootstrap5\LinkPager;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var OrderRationingDataClose $model
 * @var ActiveDataProvider $dataProvider
 * @var string $forceReload
 */

$this->title = 'Списание Н/Ч';

$this->params['breadcrumbs'][] = $this->title;

?>

<?= WriteOffNormaButton::widget(['active' => 'order']) ?>

<?php Pjax::begin(['id' => $forceReload]); ?>
<?= GridView::widget([
    'filterModel' => $model,
    'dataProvider' => $dataProvider,
    'tableOptions' => Yii::$app->params['gridViewTableOptions'],
    'columns' => [
        ['attribute' => 'order'],
        ['attribute' => 'norma'],
        ['attribute' => 'year',],
        ['class' => MonthColumn::class,],
    ],
    'pager' => [
        'class' => LinkPager::class,
        'options' => ['style' => 'margin-left:10px;']
    ]
]);
?>
<?php Pjax::end(); ?>
