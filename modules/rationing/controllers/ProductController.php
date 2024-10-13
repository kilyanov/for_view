<?php

declare(strict_types=1);

namespace app\modules\rationing\controllers;

use app\common\rbac\CollectionRolls;
use app\modules\rationing\forms\RationingProductForm;
use app\modules\rationing\models\RationingProduct;
use app\modules\rationing\models\search\RationingProductSearch;
use kilyanov\architect\controller\ApplicationController;
use yii\web\BadRequestHttpException;
use yii\web\Response;

class ProductController extends ApplicationController
{
    /**
     * @return void
     */
    public function init(): void
    {
        $this->setModelClass(RationingProduct::class);
        $this->setSearchModelClass(RationingProductSearch::class);
        parent::init();
        $this->setListAccess([CollectionRolls::ROLE_SECONDARY_CIL]);
        $this->layout = '/main-fluid';
    }

    /**
     * @param $action
     * @return bool
     * @throws BadRequestHttpException
     */
    public function beforeAction($action): bool
    {
        if (parent::beforeAction($action)) {
            if ($action->id === 'copy') {
                $this->setModelClass(RationingProductForm::class);
            }
            return true;
        }
        return false;
    }

    /**
     * @param string $name
     * @param string $unitId
     * @param string $impactId
     * @param string|null $productId
     * @param string|null $productNodeId
     * @param string|null $productBlockId
     * @return Response
     */
    public function actionList(
        string $name,
        string $unitId,
        string $impactId,
        ?string $productId = null,
        ?string $productNodeId = null,
        ?string $productBlockId = null): Response
    {
        $models = RationingProduct::find()
            ->andFilterWhere(['like', RationingProduct::tableName() . '.[[name]]', $name])
            ->andFilterWhere([RationingProduct::tableName() . '.[[unitId]]' => $unitId])
            ->andFilterWhere([RationingProduct::tableName() . '.[[productId]]' => $productId])
            ->andFilterWhere([RationingProduct::tableName() . '.[[productNodeId]]' => $productNodeId])
            ->andFilterWhere([RationingProduct::tableName() . '.[[productBlockId]]' => $productBlockId])
            ->andFilterWhere([RationingProduct::tableName() . '.[[impactId]]' => $impactId])
            ->hidden()
            ->limit(10)
            ->asArray()
            ->all();

        $data['results'] = array_map(
            function ($model) {
                return [
                    'id' => $model['id'],
                    'text' => $model['name']
                ];
            },
            $models
        );

        return $this->asJson($data);
    }
}
