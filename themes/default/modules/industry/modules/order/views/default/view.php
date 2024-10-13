<?php

declare(strict_types=1);

use app\common\rbac\CollectionRolls;
use app\modules\industry\modules\order\widgets\GroupButtonWidget;
use yii\widgets\DetailView;
use app\modules\industry\models\OrderList;

/** @var $model OrderList * */

$this->title = 'Просмотр заказа №' . $model->number;

$this->params['breadcrumbs'][] = $this->title;

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header border-0">
                    <?= GroupButtonWidget::widget([
                        'configUrl' => ['orderId' => $model->id],
                        'active' => 'view'
                    ]) ?>
                </div>
                <div class="card-body table-responsive p-0">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            [
                                'label' => 'Тип',
                                'value' => $model->getType(),
                            ],
                            'number',
                            'numberScore',
                            [
                                'label' => 'Контракт',
                                'value' => $model->contractRelation->getFullName(),
                            ],
                            'year',
                            [
                                'label' => 'Статус',
                                'value' => $model->getStatus(),
                            ],
                            [
                                'label' => 'Скрыт',
                                'value' => $model->getHidden(),
                                'visible' => Yii::$app->user->can(CollectionRolls::ROLE_ROOT)
                            ],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
