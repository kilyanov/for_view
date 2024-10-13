<?php

declare(strict_types=1);

use app\common\architect\LinkDeleteAll;
use app\common\grid\ActionColumn;
use app\common\grid\HiddenColumn;
use app\common\grid\PersonalGroupColumn;
use app\common\grid\PersonalSpecialColumn;
use app\common\grid\StatusColumn;
use app\common\grid\UnitColumn;
use app\common\interface\StatusAttributeInterface;
use app\modules\personal\models\Personal;
use app\widgets\GroupButtonWidget;
use kilyanov\architect\widgets\ModalWidget;
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

$this->title = 'Персонал';

$this->params['breadcrumbs'][] = $this->title;

?>

<?= GroupButtonWidget::widget([
    'access' => $listAccess,
    'visible' => ['move' => false, ]
]) ?>

<?php Pjax::begin(['id' => $forceReload]); ?>
<?= GridView::widget([
    'filterModel' => $model,
    'dataProvider' => $dataProvider,
    'tableOptions' => Yii::$app->params['gridViewTableOptions'],
    'rowOptions' => function ($model) {
        /** @var Personal $model */
        $class = [];
        if ($model->typeSalary == Personal::TYPE_SALARY_YES) {
            $class = ['class' => 'table-success'];
        }
        if ($model->status == StatusAttributeInterface::STATUS_NOT_ACTIVE) {
            $class = ['class' => 'table-danger'];
        }
        return $class;
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
            'attribute' => 'type',
            'value' => function ($model) {
                /** @var Personal $model */
                return $model->getType();
            }
        ],
        [
            'multiple' => false,
            'showParent' => true,
            'class' => UnitColumn::class,
        ],
        [
            'class' => PersonalGroupColumn::class,
        ],
        [
            'class' => PersonalSpecialColumn::class,
        ],
        'discharge',
        'fistName',
        'lastName',
        'secondName',
        [
            'format' => ['decimal', 2],
            'attribute' => 'salary'
        ],
        'ratio',
        [
            'class' => StatusColumn::class,
        ],
        'description',
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

