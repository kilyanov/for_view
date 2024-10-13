<?php

declare(strict_types=1);

use app\common\architect\LinkDeleteAll;
use app\common\grid\ActionColumn;
use app\common\grid\PageSummaryDataColumn;
use app\modules\industry\models\PresentationBookDataProduct;
use app\widgets\GroupButtonWidget;
use kilyanov\architect\widgets\ModalWidget;
use yii\bootstrap5\LinkPager;
use yii\data\ActiveDataProvider;
use yii\grid\CheckboxColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var PresentationBookDataProduct $model
 * @var ActiveDataProvider $dataProvider
 * @var string $forceReload
 * @var array $listAccess
 */

$this->title = 'Просмотр работ';

$this->params['breadcrumbs'][] = [
    'label' => 'Книга предъявлений',
    'url' => [
        '/industry/presentation/default/index',
        'groupId' => $model->bookRelation->groupId
    ]
];

$this->params['breadcrumbs'][] = $this->title;

?>

<?php Pjax::begin(['id' => $forceReload]); ?>
<?= GroupButtonWidget::widget([
    'access' => $listAccess,
    'configUrl' => ['bookId' => $model->bookId],
    'visible' => ['create' => false, 'move' => false, 'export' => false],
]); ?>
<?= GridView::widget([
    'filterModel' => $model,
    'dataProvider' => $dataProvider,
    'showFooter' => true,
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
            'header' => 'Номер заказа',
            'value' => function ($model) {
                /** @var PresentationBookDataProduct $model */
                return $model->orderRationingDataRelation->rationingRelation->orderRelation->getFullName();
            },
            'filter' => false
        ],
        [
            'header' => 'Пункт/параграф',
            'value' => function ($model) {
                /** @var PresentationBookDataProduct $model */
                return $model->orderRationingDataRelation->getNumber();
            },
            'filter' => false
        ],
        [
            'attribute' => 'orderRationingDataId',
            'value' => function ($model) {
                /** @var PresentationBookDataProduct $model */
                return $model->orderRationingDataRelation->name;
            },
            'filter' => false
        ],
        [
            'header' => 'Специальность',
            'value' => function ($model) {
                /** @var PresentationBookDataProduct $model */
                return $model->orderRationingDataRelation->specialRelation->name;
            },
            'filter' => false
        ],
        [
            'header' => 'Разряд',
            'value' => function ($model) {
                /** @var PresentationBookDataProduct $model */
                return $model->orderRationingDataRelation->category;
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
