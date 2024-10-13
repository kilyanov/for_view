<?php

declare(strict_types=1);

use app\common\architect\LinkDeleteAll;
use app\common\grid\ActionColumn;
use app\common\grid\HiddenColumn;
use app\common\grid\StatusColumn;
use app\widgets\GroupButtonWidget;
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

?>

<div class="card" style="margin-top: 10px;">
    <div class="card-header">
        <h5 class="card-title">
            Сведения о забраковки СИ
            Вид воздействия на СИ
            <?= GroupButtonWidget::widget([
                'configUrl' => ['deviceId' => $model->deviceId],
                'access' => $listAccess,
                'visible' => ['move' => false, 'export' => false, 'index' => false]
            ]) ?>
        </h5>
    </div>
    <div class="card-body">

        <?php Pjax::begin(['id' => $forceReload]); ?>
        <?= GridView::widget([
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
                    'attribute' => 'rejection_date',
                ],
                'description',
                [
                    'class' => StatusColumn::class
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
    </div>
</div>
