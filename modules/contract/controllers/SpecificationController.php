<?php

declare(strict_types=1);

namespace app\modules\contract\controllers;

use app\modules\contract\models\Contract;
use app\modules\contract\models\ContractSpecification;
use app\modules\contract\models\search\ContractSpecificationSearch;
use kilyanov\architect\controller\ApplicationController;
use Yii;
use yii\web\NotFoundHttpException;

/**
 *
 * @property-read array $cfgParams
 */
class SpecificationController extends ApplicationController
{
    /**
     * @throws NotFoundHttpException
     */
    public function init(): void
    {
        parent::init();
        $this->setModelClass(ContractSpecification::class);
        $this->setSearchModelClass(ContractSpecificationSearch::class);

        $cfgParams = $this->getCfgParams();
        $this->setCfgSearchModel($cfgParams);
        $this->setCfgModel($cfgParams);
    }

    /**
     * @throws NotFoundHttpException
     */
    protected function getCfgParams(): array
    {
        $contractId = Yii::$app->getRequest()->get('contractId');
        if ($contractId === null) return [];
        $model = $this->findModelContract($contractId);
        return ['contractId' => $model->id];
    }

    /**
     * @param string $id
     * @return Contract
     * @throws NotFoundHttpException
     */
    protected function findModelContract(string $id): Contract
    {
        /** @var Contract $model */
        $model = Contract::find()->ids($id)->one();
        if ($model === null) {
            throw new NotFoundHttpException("Records with ID {$id} not found.");
        }

        return $model;
    }
}
