<?php

declare(strict_types=1);

use app\common\architect\LinkDeleteAll;
use app\common\grid\ActionColumn;
use app\common\grid\PageSummaryDataColumn;
use app\modules\industry\models\PresentationBookDataDeviceRepair;
use app\modules\industry\models\search\PresentationBookDataDeviceRepairSearch;
use app\widgets\GroupButtonWidget;
use kilyanov\architect\widgets\ModalWidget;
use yii\bootstrap5\LinkPager;
use yii\data\ActiveDataProvider;
use yii\grid\CheckboxColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var PresentationBookDataDeviceRepairSearch $model
 * @var ActiveDataProvider $dataProvider
 * @var string $forceReload
 * @var array $listAccess
 */

$this->params['breadcrumbs'][] = [
    'label' => 'Книга предъявлений',
    'url' => [
        '/industry/presentation/default/index',
        'groupId' => $model->bookRelation->groupId
    ]
];

$this->title = $model->bookRelation->deviceRepairRelation->deviceNameRelation->name . '
 ' . $model->bookRelation->deviceRepairRelation->deviceTypeRelation->name;

$this->params['breadcrumbs'][] = $this->title;

?>

<?php Pjax::begin(['id' => $forceReload]); ?>
<?php if (count($dataProvider->getModels()) === 0): ?>
    <?= GroupButtonWidget::widget([
        'access' => $listAccess,
        'configUrl' => ['bookId' => $model->bookId],
        'visible' => ['create' => true, 'move' => false, 'export' => false],
    ]); ?>
<?php endif; ?>
<?= GridView::widget([
    'filterModel' => $model,
    'dataProvider' => $dataProvider,
    'showFooter' => true,
    'tableOptions' => Yii::$app->params['gridViewTableOptions'],
    'columns' => [
        [
            'class' => CheckboxColumn::class,
            'headerOptions' => ['style' => 'width:20px'],
        ],
        [
            'class' => ActionColumn::class,
        ],
        [
            'attribute' => 'rationingDeviceId',
            'value' => function ($model) {
                /** @var PresentationBookDataDeviceRepair $model */
                return $model->rationingDeviceRelation->paragraph;
            },
            'filter' => false
        ],
        [
            'attribute' => 'rationingDeviceDataId',
            'value' => function ($model) {
                /** @var PresentationBookDataDeviceRepair $model */
                return $model->rationingDeviceDataRelation->operationNumber . '. ' .
                    $model->rationingDeviceDataRelation->name;
            },
            'filter' => false
        ],
        [
            'header' => 'Специальность',
            'value' => function ($model) {
                /** @var PresentationBookDataDeviceRepair $model */
                return $model->rationingDeviceDataRelation->specialRelation->name;
            },
            'filter' => false
        ],
        [
            'header' => 'Разряд',
            'value' => function ($model) {
                /** @var PresentationBookDataDeviceRepair $model */
                return $model->rationingDeviceDataRelation->category;
            },
            'filter' => false
        ],
        [
            'class' => PageSummaryDataColumn::class,
            'attribute' => 'norma',
            'filter' => false
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
