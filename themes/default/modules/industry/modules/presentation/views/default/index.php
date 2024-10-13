<?php

declare(strict_types=1);

use app\common\architect\LinkDeleteAll;
use app\common\grid\ActionColumn;
use app\common\grid\ImpactColumn;
use app\common\grid\PageSummaryDataColumn;
use app\common\grid\PersonalColumn;
use app\common\grid\StatusColumn;
use app\common\grid\UnitColumn;
use app\common\grid\UnitOwnerColumn;
use app\modules\industry\models\PresentationBook;
use app\modules\industry\modules\presentation\widgets\GroupButtonWidget;
use app\modules\industry\modules\presentation\widgets\PresentBookButtonListWidget;
use kilyanov\architect\entity\UrlEntity;
use kilyanov\architect\widgets\ModalWidget;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\bootstrap5\LinkPager;
use yii\data\ActiveDataProvider;
use yii\grid\CheckboxColumn;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;

/**
 * @var PresentationBook $model
 * @var ActiveDataProvider $dataProvider
 * @var string $forceReload
 * @var array $listAccess
 */

$this->title = 'Книги предъявлений';

$this->params['breadcrumbs'][] = $this->title;

Pjax::begin(['id' => $forceReload]); ?>

<?= PresentBookButtonListWidget::widget([
    'unitId' => Yii::$app->user->identity->unitId
]); ?>

<?php if ($model->groupId !== null): ?>
    <?= GroupButtonWidget::widget([
        'access' => $listAccess,
        'configUrl' => ['groupId' => $model->groupId],
        'visible' => ['export' => true],
    ]); ?>
<?php else: ?>
    <?= (new UrlEntity([
        'name' => 'Экспорт',
        'url' => ArrayHelper::merge(['export'], ['groupId' => $model->groupId]),
        'options' => ['data-pjax' => 0, 'class' => 'btn btn-success float-end'],
    ]))->make(); ?>
<?php endif; ?>

<?php $form = ActiveForm::begin([
    'action' => [
        'index',
        'groupId' => $model->groupId
    ],
    'method' => 'get',
]); ?>
<div class="row">
    <div class="col-3">
        <?= $form->field($model, 'year') ?>
    </div>
    <div class="col-3">
        <?= $form->field($model, 'month')->dropDownList(PresentationBook::getMonthList(), ['prompt' => '']) ?>
    </div>
    <div class="col-3">
        <div class="form-group" style="margin-top: 28px;">
            <?= Html::submitButton('Поиск', ['class' => 'btn btn-primary']) ?>

            <?php /*LinkResetWidget::widget([
                'configUrl' => ['', 'groupId' => $model->groupId]
            ])*/ ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>

<?= GridView::widget([
    'filterModel' => $model,
    'dataProvider' => $dataProvider,
    'showFooter' => true,
    'rowOptions' => function ($model) {
        /** @var PresentationBook $model */
        return $model->colorCell;
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
            'attribute' => 'orderId',
            'value' => function ($model) {
                /** @var PresentationBook $model */
                return $model->orderRelation->number;
            }
        ],
        [
            'class' => PersonalColumn::class
        ],
        [
            'attribute' => 'impactId',
            'class' => ImpactColumn::class
        ],
        [
            'class' => UnitColumn::class
        ],
        [
            'attribute' => 'name',
        ],
        [
            'attribute' => 'number',
        ],
        [
            'attribute' => 'inventoryNumber',
        ],
        [
            'label' => 'Владелец',
            'class' => UnitOwnerColumn::class,
            'filter' => false,
        ],
        [
            'class' => PageSummaryDataColumn::class,
            'attribute' => 'norma',
            'value' => function ($model) {
                return $model->norma;
            },
            'url' => function ($model) {
                return $model->getUrlPresentData();
            },
            'format' => 'raw',
            'cfgAttrUrl' => 'bookId',
        ],
        [
            'attribute' => 'date',
        ],
        [
            'class' => StatusColumn::class,
            'filter' => true,
            'filterInputOptions' => ['multiple' => true]
        ],
        [
            'attribute' => 'comment',
        ],
    ],
    'pager' => [
        'class' => LinkPager::class,
        'options' => ['style' => 'margin-left:10px;']
    ]
]);
?>


<?= (new LinkDeleteAll())->setAccess($listAccess)->make(); ?>

<?php Pjax::end(); ?>

<?= ModalWidget::widget() ?>
