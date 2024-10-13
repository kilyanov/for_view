<?php

declare(strict_types=1);

use app\common\architect\LinkDeleteAll;
use app\common\grid\ActionColumn;
use app\common\grid\DeviceNameColumn;
use app\common\grid\DevicePropertyColumn;
use app\common\grid\DeviceRejectColumn;
use app\common\grid\DeviceToUnitColumn;
use app\common\grid\DeviceTypeColumn;
use app\common\grid\DeviceVerificationColumn;
use app\common\grid\DeviceVerificationNextColumn;
use app\common\grid\DeviceVerificationPeriodColumn;
use app\common\grid\HiddenColumn;
use app\modules\device\architect\FilterButton;
use app\modules\device\models\Device;
use app\modules\device\widgets\FilterWidget;
use app\widgets\GroupButtonWidget;
use kilyanov\architect\widgets\ModalWidget;
use yii\bootstrap5\Html;
use yii\bootstrap5\LinkPager;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\grid\CheckboxColumn;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;

/**
 * @var ActiveQuery $model
 * @var ActiveDataProvider $dataProvider
 * @var string $forceReload
 * @var array $listAccess
 */

$this->title = 'Список СИ';

$this->params['breadcrumbs'][] = $this->title;

?>
<?php Pjax::begin(['id' => $forceReload]); ?>
<?= GroupButtonWidget::widget([
    'access' => $listAccess,
    'configUrl' => Yii::$app->request->getQueryParams(),
    'exceptionConfigUrl' => ['index'],
    'visible' => ['move' => false,]
]) ?>

<?= FilterWidget::widget(); ?>

<?= (new FilterButton())
    ->setUrl(ArrayHelper::merge(['filter'], Yii::$app->request->getQueryParams()))
    ->setAccess($listAccess)
    ->make();
 ?>

<?= GridView::widget([
    'filterModel' => $model,
    'dataProvider' => $dataProvider,
    'rowOptions' => function ($model) {
        /** @var Device $model */
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
        ],
        [
            'class' => DeviceToUnitColumn::class,
        ],
        [
            'class' => DeviceNameColumn::class
        ],
        [
            'format' => 'raw',
            'class' => DeviceTypeColumn::class,
            'filter' => true
        ],
        [
            'class' => DevicePropertyColumn::class
        ],
        [
            'format' => 'raw',
            'attribute' => 'stateRegister',
        ],
        [
            'attribute' => 'factoryNumber',
        ],
        [
            'format' => 'raw',
            'attribute' => 'inventoryNumber',
            'value' => function ($model) {
                return !empty($model->inventoryNumber) ? Html::a(
                    $model->inventoryNumber,
                    [
                        '/manual/statement/index/',
                        'searchInventoryNumber' => $model->inventoryNumber
                    ],
                    ['data-pjax' => 0, 'target' => '_blank', 'class' => 'btn btn-secondary']
                ) : '';
            }
        ],
        [
            'class' => DeviceVerificationPeriodColumn::class
        ],
        [
            'class' => DeviceVerificationColumn::class
        ],
        [
            'class' => DeviceVerificationNextColumn::class
        ],
        [
            'class' => DeviceRejectColumn::class
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

<?= ModalWidget::widget() ?>
