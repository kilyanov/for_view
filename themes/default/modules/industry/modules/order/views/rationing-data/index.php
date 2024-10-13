<?php

declare(strict_types=1);

use app\common\architect\LinkDeleteAll;
use app\common\grid\ActionColumn;
use app\common\grid\HiddenColumn;
use app\common\grid\MachineColumn;
use app\common\grid\PageSummaryDataColumn;
use app\common\grid\PersonalSpecialColumn;
use app\common\grid\UnitColumn;
use app\modules\industry\models\OrderRationingData;
use app\modules\industry\modules\order\factory\ActionMenuFactory;
use app\modules\rationing\models\RationingProductData;
use app\widgets\GroupButtonWidget;
use kilyanov\architect\widgets\ModalWidget;
use yii\bootstrap5\Html;
use yii\bootstrap5\LinkPager;
use yii\data\ActiveDataProvider;
use yii\grid\CheckboxColumn;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;

/**
 * @var OrderRationingData $model
 * @var ActiveDataProvider $dataProvider
 * @var string $forceReload
 * @var array $listAccess
 */

$this->title = $model->rationingRelation->getFullName();

$this->params['breadcrumbs'][] = ['label' => 'Заказы', 'url' => ['/industry/order']];
$this->params['breadcrumbs'][] = [
    'label' => 'Нормировки по заказу ' . $model->rationingRelation->orderRelation->number,
    'url' => ['/industry/order/rationing/index', 'orderId' => $model->rationingRelation->orderId]
];
$this->params['breadcrumbs'][] = $this->title;

?>

<?php Pjax::begin(['id' => $forceReload]); ?>

<?= GroupButtonWidget::widget([
    'access' => $listAccess,
    'configUrl' => ['rationingId' => $model->rationingId],
    'visible' => ['import' => true]
]) ?>

<?= GridView::widget([
    'filterModel' => $model,
    'dataProvider' => $dataProvider,
    'showFooter' => true,
    'rowOptions' => function ($model) {
        return $model->colorCell;
    },
    'tableOptions' => Yii::$app->params['gridViewTableOptions'],
    'columns' => [
        [
            'headerOptions' => ['style' => 'width:20px'],
            'class' => CheckboxColumn::class,
        ],
        [
            'class' => ActionColumn::class,
            'factory' => ActionMenuFactory::class,
        ],

        [
            'headerOptions' => ['style' => 'width:100px'],
            'attribute' => 'point',
        ],
        [
            'headerOptions' => ['style' => 'width:100px'],
            'attribute' => 'subItem',
        ],
        [
            'format' => 'raw',
            'attribute' => 'name',
            'value' => function ($model) {
                if ($model->checkList === 1) {
                    return Html::a($model->name,
                        [
                            '/industry/rationing-product-military',
                            'rationingDataId' => $model->id,
                        ],
                        ['target' => '_blank', 'data-pjax' => 0]);
                } else {
                    return $model->name;
                }
            },
            'filter' => true
        ],
        [
            'headerOptions' => ['style' => 'width:100px'],
            'class' => UnitColumn::class,
            'value' => function ($model) {
                /** @var $model RationingProductData */
                return ($model->type == RationingProductData::TYPE_SUB_POINT) ?
                    $model->unitRelation?->name : '';
            },
            'showParent' => true,
        ],
        [
            'headerOptions' => ['style' => 'width:70px'],
            'attribute' => 'ed',
            'filter' => false
        ],
        [
            'headerOptions' => ['style' => 'width:70px'],
            'attribute' => 'countItems',
            'filter' => false
        ],
        [
            'headerOptions' => ['style' => 'width:70px'],
            'attribute' => 'periodicity',
            'filter' => false
        ],
        [
            'headerOptions' => ['style' => 'width:70px'],
            'attribute' => 'specialId',
            'class' => PersonalSpecialColumn::class,
        ],
        [
            'headerOptions' => ['style' => 'width:70px'],
            'attribute' => 'category',
            'filter' => false
        ],
        [
            'headerOptions' => ['style' => 'width:70px'],
            'format' => ['decimal', 2],
            'attribute' => 'norma',
            'filter' => false
        ],
        [
            'headerOptions' => ['style' => 'width:70px'],
            'class' => PageSummaryDataColumn::class,
            'format' => ['decimal', 2],
            'attribute' => 'normaAll',
            'filter' => false
        ],
        [
            'headerOptions' => ['style' => 'width:70px'],
            'format' => 'raw',
            'class' => PageSummaryDataColumn::class,
            'attribute' => 'closeNorma',
            'filter' => false,
            'url' => '/industry/order/rationing-data-close/index',
            'role' => 'modal-remote'
        ],
        [
            'headerOptions' => ['style' => 'width:70px'],
            'class' => PageSummaryDataColumn::class,
            'attribute' => 'stayNorma',
            'format' => ['decimal', 2],
            'filter' => false
        ],
        [
            'class' => MachineColumn::class,
            'productId' => function ($model) {
                /** @var $model OrderRationingData */
                return $model->rationingRelation->productId;
            },
            'visibleFullName' => false,
            'headerOptions' => ['style' => 'width:70px'],
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
<div class="float-end">
    <?= Html::a(
        'Установить новый номер пункта',
        Url::to(['set-newNumber-item', 'rationingId' => $model->rationingId]),
        [
            'class' => 'btn btn-info',
            'role' => 'modal-remote-bulk',
            'data-confirm' => false,
            'data-method' => false,
            'data-request-method' => 'post',
            'data-confirm-title' => 'Подтверждение установки нового номера!',
            'data-confirm-message' => 'Вы уверены что хотите установить новой номера?'
        ]
    ) ?>
    <?= Html::a(
        'Закрыть Н/Ч',
        Url::to(['all-close-norma', 'rationingId' => $model->rationingId]),
        [
            'class' => 'btn btn-info',
            'role' => 'modal-remote-bulk',
            'data-confirm' => false,
            'data-method' => false,
            'data-request-method' => 'post',
            'data-confirm-title' => 'Подтверждение закрытия Н/Ч!',
            'data-confirm-message' => 'Вы уверены что хотите закрыть выбранные Н/Ч?'
        ]
    ) ?>

    <?= Html::a(
        'Отменить списание Н/Ч',
        Url::to(['all-cancel-close-norma', 'rationingId' => $model->rationingId]),
        [
            'class' => 'btn btn-danger',
            'role' => 'modal-remote-bulk',
            'data-confirm' => false,
            'data-method' => false,
            'data-request-method' => 'post',
            'data-confirm-title' => 'Подтверждение отмены списания Н/Ч!',
            'data-confirm-message' => 'Вы уверены что хотите отменить списание Н/Ч?'
        ]
    ) ?>
</div>

<?= ModalWidget::widget() ?>
