<?php

declare(strict_types=1);

namespace app\modules\industry\modules\product\controllers;

use app\modules\industry\models\RepairProduct;
use app\modules\industry\models\search\RepairProductSearch;
use kilyanov\architect\actions\base\ImportAction;
use kilyanov\architect\controller\ApplicationController;
use yii\helpers\ArrayHelper;
use yii\web\Response;

/**
 * Default controller for the `product` module
 */
class DefaultController extends ApplicationController
{
    /**
     * @return void
     */
    public function init(): void
    {
        $this->setModelClass(RepairProduct::class);
        $this->setSearchModelClass(RepairProductSearch::class);
        parent::init();
    }

    /**
     * @return array|string[]
     */
    public function actions(): array
    {
        $actions = parent::actions();
        return ArrayHelper::merge(
            $actions,
            [
                'import' => [
                    'class' => ImportAction::class,
                    'fileMap' => require (__DIR__ . '/../map/product.php'),
                    'importModel' => RepairProduct::class,
                ],
            ]
        );
    }

    /**
     * @param string|null $number
     * @return Response
     */
    public function actionList(?string $number): Response
    {
        $query = RepairProduct::find()
            ->andFilterWhere(['like', RepairProduct::tableName()  . '.[[number]]', $number]);
        $result['results'] = array_map(
            function (RepairProduct $model) {
                return [
                    'id' => $model->id,
                    'text' => $model->getFullName()
                ];
            },
            $query->hidden()
                ->limit(20)
                ->all()
        );

        return $this->asJson($result);
    }
}
