<?php

declare(strict_types=1);

use app\common\architect\LinkDeleteAll;
use app\common\grid\ActionColumn;
use app\common\grid\ResourceDataColumn;
use app\modules\application\models\ApplicationData;
use app\widgets\GroupButtonWidget;
use kilyanov\architect\entity\UrlEntity;
use kilyanov\architect\widgets\ModalWidget;
use yii\bootstrap5\Html;
use yii\bootstrap5\LinkPager;
use yii\data\ActiveDataProvider;
use yii\grid\CheckboxColumn;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\Pjax;

/**
 * @var ApplicationData $model
 * @var ActiveDataProvider $dataProvider
 * @var string $forceReload
 * @var array $listAccess
 */

$this->title = 'Обеспечение по заказу';
$this->params['breadcrumbs'][] = ['label' => 'Заказы', 'url' => ['/industry/order']];
$this->params['breadcrumbs'][] = $this->title;

?>

<?php Pjax::begin(['id' => $forceReload]); ?>
<?= (new UrlEntity([
    'name' => 'Показать не полученное',
    'url' => ArrayHelper::merge(
        ['index'],
        [
            'orderId' => $model->orderId,
            'ApplicationDataSearch[quantityReceipt]' => '0.000000'
        ]
    ),
    'options' => ['class' => 'btn btn-danger'],
]))->make(); ?>
<?= GroupButtonWidget::widget([
    'access' => $listAccess,
    'configUrl' => ['orderId' => $model->orderId,],
    'visible' => ['move' => false, 'import' => false, 'create' => false],
]) ?>

<?= GridView::widget([
    'filterModel' => $model,
    'dataProvider' => $dataProvider,
    'tableOptions' => Yii::$app->params['gridViewTableOptions'],
    'rowOptions' => function ($model) {
        /** @var ApplicationData $model */
        return $model->getColorRow();
    },
    'columns' => [
        [
            'class' => CheckboxColumn::class,
            'headerOptions' => ['style' => 'width:20px'],
        ],
        [
            'class' => ActionColumn::class,
        ],
        [
            'class' => ResourceDataColumn::class
        ],
        [
            'header' => 'Ед. изм.',
            'value' => function ($model) {
                /** @var ApplicationData $model */
                return $model->resourceRelation->ed;
            }
        ],
        [
            'attribute' => 'quantity',
        ],
        [
            'format' => 'raw',
            'attribute' => 'deliveryTime',
        ],
        [
            'attribute' => 'quantityReceipt',
        ],
        [
            'attribute' => 'receiptDate',
        ],
        [
            'attribute' => 'comment',
            'value' => function ($model) {
                /** @var ApplicationData $model */
                return $model->comment === null ? $model->applicationRelation->comment : $model->comment;
            }
        ],
        [
            'format' => ['decimal', 2],
            'attribute' => 'percent',
        ]
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
        'Получить все',
        Url::to(['receive-all', 'applicationId' => $model->applicationId]),
        [
            'class' => 'btn btn-info',
            'role' => 'modal-remote-bulk',
            'data-confirm' => false,
            'data-method' => false,
            'data-request-method' => 'post',
            'data-confirm-title' => 'Подтверждение действия!',
            'data-confirm-message' => 'Вы уверены что хотите получить все?'
        ]
    ) ?>
    <?= Html::a(
        'Отменить получение полностью',
        Url::to(['receive-cancel-all', 'applicationId' => $model->applicationId]),
        [
            'class' => 'btn btn-danger',
            'role' => 'modal-remote-bulk',
            'data-confirm' => false,
            'data-method' => false,
            'data-request-method' => 'post',
            'data-confirm-title' => 'Подтверждение действия!',
            'data-confirm-message' => 'Вы уверены что хотите отменить получение полностью?'
        ]
    ) ?>
    <?= Html::a(
        'Получить выбранные',
        Url::to(['receive-custom', 'applicationId' => $model->applicationId]),
        [
            'class' => 'btn btn-info',
            'role' => 'modal-remote-bulk',
            'data-confirm' => false,
            'data-method' => false,
            'data-request-method' => 'post',
            'data-confirm-title' => 'Подтверждение действия!',
            'data-confirm-message' => 'Вы уверены что хотите получить выбранное имущество?'
        ]
    ) ?>
    <?= Html::a(
        'Отменить выбранное получение',
        Url::to(['receive-cancel-custom', 'applicationId' => $model->applicationId]),
        [
            'class' => 'btn btn-danger',
            'role' => 'modal-remote-bulk',
            'data-confirm' => false,
            'data-method' => false,
            'data-request-method' => 'post',
            'data-confirm-title' => 'Подтверждение действия!',
            'data-confirm-message' => 'Вы уверены что хотите отменить выбранное получение?'
        ]
    ) ?>
</div>

<?= ModalWidget::widget() ?>
