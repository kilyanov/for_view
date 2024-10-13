<?php

use app\modules\industry\models\PresentationBook;
use app\modules\industry\models\search\PresentationBookSearch;
use kilyanov\architect\entity\UrlEntity;
use miloschuman\highcharts\Highcharts;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\helpers\ArrayHelper;

/**
 * @var array $series
 * @var PresentationBook $model
 */


$this->title = 'Статистика';

$this->params['breadcrumbs'][] = ['label' => 'Книга предъявлений', 'url' => ['/industry/presentation']];
$this->params['breadcrumbs'][] = $this->title;

?>

<?php $form = ActiveForm::begin([
    'action' => ['chart'],
    'method' => 'get',
]); ?>

<?= $form->field($model, 'year') ?>

<?= $form->field($model, 'month')->dropDownList(PresentationBook::getMonthList(), ['prompt' => '']) ?>

<?= $form->field($model, 'typeView')->dropDownList(PresentationBookSearch::getListTypeView(), ['prompt' => '']) ?>

<div class="form-group">
    <?= Html::submitButton('Поиск', ['class' => 'btn btn-primary']) ?>

    <?= (new UrlEntity([
        'name' => 'Сбросить',
        'url' => ['index'],
        'options' => ['class' => 'btn btn-secondary'],
    ]))->make()
    ?>
</div>

<?php ActiveForm::end(); ?>

<?= Highcharts::widget([
    'options' => [
        'chart' => ['type' => 'column'],
        'title' => ['text' => 'Статистика предъявлений'],
        'xAxis' => [
            'categories' => ['Предъявления']
        ],
        'yAxis' => [
            'title' => ['text' => 'Кол-во']
        ],
        'series' => $series
    ]
]);
?>


