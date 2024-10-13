<?php

declare(strict_types=1);

use yii\bootstrap5\LinkPager;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;

/**
 * @var ActiveDataProvider $dataProvider
 */

?>

<?= GridView::widget([
    'filterModel' => false,
    'dataProvider' => $dataProvider,
    'columns' => [
        'dateService',
        'comment',
    ],
    'pager' => [
        'class' => LinkPager::class,
        'options' => ['style' => 'margin-left:10px;']
    ]
]);
?>
