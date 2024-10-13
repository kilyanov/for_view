<?php

declare(strict_types=1);

namespace app\modules\industry\modules\presentation\controllers;

use app\common\rbac\CollectionRolls;
use app\modules\industry\models\PresentationBookDataDeviceRepair;
use app\modules\industry\models\search\PresentationBookDataDeviceRepairSearch;
use app\modules\industry\modules\presentation\forms\PresentBookDataDeviceRepairForm;
use kilyanov\architect\controller\ApplicationController;
use Yii;
use app\modules\industry\models\PresentationBook;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

/**
 *
 * @property-read array $cfgParams
 */
class DataDeviceRepairController extends ApplicationController
{
    /**
     * @var PresentationBook|null
     */
    public ?PresentationBook $book;

    /**
     * @return void
     * @throws NotFoundHttpException
     */
    public function init(): void
    {
        $this->setModelClass(PresentationBookDataDeviceRepair::class);
        $this->setSearchModelClass(PresentationBookDataDeviceRepairSearch::class);
        parent::init();
        $cfgParams = $this->getCfgParams();
        $this->setCfgSearchModel($cfgParams);
        $this->setCfgModel($cfgParams);
        $this->setListAccess([CollectionRolls::ROLE_ENGINEER_CIL]);
    }

    /**
     * @throws BadRequestHttpException
     */
    public function beforeAction($action): bool
    {
        if ($action->id === 'create') {
            $this->setModelClass(PresentBookDataDeviceRepairForm::class);
        }
        return parent::beforeAction($action);
    }

    /**
     * @return array
     * @throws NotFoundHttpException
     */
    protected function getCfgParams(): array
    {
        $bookId = Yii::$app->getRequest()->get('bookId');
        if ($bookId === null) return [];
        $this->book = PresentationBook::find()->ids($bookId)->one();
        if ($this->book === null) {
            throw new NotFoundHttpException("Records with ID {$bookId} not found.");
        }
        return [
            'bookId' => $this->book->id
        ];
    }
}
