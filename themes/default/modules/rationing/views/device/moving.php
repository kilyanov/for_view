<?php

declare(strict_types=1);

use kilyanov\sortable\widgets\SortableWidget;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use app\modules\rationing\models\RationingDevice;

/**
 * @var $model RationingDevice
 * @var ActiveDataProvider $dataProvider
 * @var string $forceReload
 */

$this->title = 'Режим перемещения';

$this->params['breadcrumbs'][] = [
    'label' => 'Нормировки по ремонту СИ',
    'url' => ['/rationing/device']
];

$this->params['breadcrumbs'][] = $this->title;

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header border-0">

                </div>
                <div class="card-body table-responsive p-0">
                    <?= SortableWidget::widget([
                        'options' => ['class' => 'js-sort'],
                        'items' => $dataProvider->getModels(),
                        'attributeContent' => static function ($item): string {
                            return RationingDevice::getFullNameMoving($item);
                        },
                        'clientEvents' => [
                            'update' => 'function (event, ui) { jQuery(this).sortableWidget({url: \'' . Url::to(['moving-update']) . '\'}) }',
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>

