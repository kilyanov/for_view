<?php

declare(strict_types=1);

use kilyanov\sortable\widgets\SortableWidget;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\helpers\Url;
use app\modules\personal\modules\group\models\PersonalGroup;

/**
 * @var ActiveQuery $model
 * @var ActiveDataProvider $dataProvider
 * @var string $forceReload
 */

$this->title = 'Группы';

$this->params['breadcrumbs'][] = $this->title;

?>

<?= SortableWidget::widget([
    'options' => ['class' => 'js-sort'],
    'items' => $dataProvider->getModels(),
    'attributeContent' => static function ($item): string {
        return PersonalGroup::getFullNameMoving($item);
    },
    'clientEvents' => [
        'update' => 'function (event, ui) { jQuery(this).sortableWidget({url: \'' . Url::to(['moving-update']) . '\'}) }',
    ],
]); ?>


