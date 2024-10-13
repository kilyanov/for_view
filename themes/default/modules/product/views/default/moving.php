<?php

declare(strict_types=1);

use app\modules\product\models\Product;
use kilyanov\sortable\widgets\SortableWidget;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\helpers\Url;

/**
 * @var ActiveQuery $model
 * @var ActiveDataProvider $dataProvider
 * @var string $forceReload
 */

$this->title = 'Изделия';

$this->params['breadcrumbs'][] = $this->title;

?>

<?= SortableWidget::widget([
    'options' => ['class' => 'js-sort'],
    'items' => $dataProvider->getModels(),
    'attributeContent' => static function ($item): string {
        return Product::getFullNameMoving($item);
    },
    'clientEvents' => [
        'update' => 'function (event, ui) { jQuery(this).sortableWidget({url: \'' . Url::to(['moving-update']) . '\'}) }',
    ],
]); ?>


