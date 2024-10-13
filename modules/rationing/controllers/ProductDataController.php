<?php

declare(strict_types=1);

namespace app\modules\rationing\controllers;

use app\common\rbac\CollectionRolls;
use app\modules\rationing\actions\UpdateAllAction;
use app\modules\rationing\models\RationingProduct;
use app\modules\rationing\models\RationingProductData;
use app\modules\rationing\models\search\RationingProductDataSearch;
use kilyanov\architect\actions\base\ExportAction;
use kilyanov\architect\controller\ApplicationController;
use Yii;
use yii\web\NotFoundHttpException;

/**
 *
 * @property-read array $cfgParams
 */
class ProductDataController extends ApplicationController
{
    /**
     * @return void
     * @throws NotFoundHttpException
     */
    public function init(): void
    {
        $this->setModelClass(RationingProductData::class);
        $this->setSearchModelClass(RationingProductDataSearch::class);
        parent::init();
        $this->setListAccess([CollectionRolls::ROLE_SECONDARY_CIL]);
        $this->layout = '/main-fluid';
        $cfgParams = $this->getCfgParams();
        $this->setCfgSearchModel($cfgParams);
        $this->setCfgModel($cfgParams);
    }

    public function actions(): array
    {
        $actions = parent::actions();

        $actions['export'] = [
            'class' => ExportAction::class,
            'rowStart' => 5,
            'writeHeader' => false,
            'templateFile' => 'rationing_product.xls',
        ];
        $actions['moving-update'] = [
            'class' => UpdateAllAction::class,
            'model' => new ($this->getModelClass())(),
            'items' => Yii::$app->getRequest()->post('items', []),
        ];

        return $actions;
    }

    /**
     * @throws NotFoundHttpException
     */
    protected function getCfgParams(): array
    {
        $rationingId = Yii::$app->getRequest()->get('rationingId');
        if ($rationingId === null) return [];
        $model = RationingProduct::find()->ids($rationingId)->one();
        /** @var $model RationingProduct */
        if ($model === null) {
            throw new NotFoundHttpException("Records with ID {$rationingId} not found.");
        }
        return [
            'rationingId' => $model->id,
        ];
    }

    /**
     * @return array
     */
    public function getExportAttribute(): array
    {
        return [
            [
                'header' => 'Пункт',
                'attribute' => 'point',
                'value' => function ($model) {
                    /** @var RationingProductData $model */
                    return match ($model->type) {
                        $model::TYPE_POINT => $model->point,
                        $model::TYPE_SUB_POINT => $model->point . '.' . $model->subItem,
                        default => '',
                    };
                }
            ],
            [
                'header' => 'Наименование операции',
                'attribute' => 'name',
            ],
            [
                'attribute' => 'name',
                'value' => function ($model) {
                    return '';
                }
            ],
            [
                'attribute' => 'name',
                'value' => function ($model) {
                    return '';
                }
            ],
            [
                'header' => 'Цех исполнитель',
                'attribute' => 'unitId',
                'value' => function ($model) {
                    /** @var RationingProductData $model */
                    return $model->unitRelation->getFullName();
                }
            ],
            [
                'header' => 'Ед. учета',
                'attribute' => 'ed',
            ],
            [
                'header' => 'Кол-во деталей в изделии',
                'attribute' => 'countItems',
            ],
            [
                'header' => 'Проц. частоты встречаемости',
                'attribute' => 'periodicity',
            ],
            [
                'header' => 'Специальность',
                'attribute' => 'specialId',
                'value' => function ($model) {
                    /** @var RationingProductData $model */
                    return $model->specialRelation->getFullName();
                }
            ],
            [
                'header' => 'Разряд работы',
                'attribute' => 'category',
            ],
            [
                'header' => 'Н/Ч на единицу',
                'attribute' => 'norma',
            ],
            [
                'header' => 'На все кол-во',
                'attribute' => 'normaAll',
            ],
        ];
    }
}
