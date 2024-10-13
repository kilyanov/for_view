<?php

declare(strict_types=1);

namespace app\modules\industry\modules\presentation\controllers;

use app\common\rbac\CollectionRolls;
use kilyanov\architect\controller\ApplicationController;
use Yii;
use app\modules\industry\models\PresentationBookDataProduct;
use app\modules\industry\models\search\PresentationBookDataProductSearch;
use app\modules\industry\models\PresentationBook;
use yii\web\NotFoundHttpException;

/**
 *
 * @property-read array $cfgParams
 */
class DataProductController extends ApplicationController
{
    /**
     * @return void
     * @throws NotFoundHttpException
     */
    public function init(): void
    {
        $this->setModelClass(PresentationBookDataProduct::class);
        $this->setSearchModelClass(PresentationBookDataProductSearch::class);
        parent::init();
        $cfgParams = $this->getCfgParams();
        $this->setCfgSearchModel($cfgParams);
        $this->setCfgModel($cfgParams);
        $this->setListAccess([CollectionRolls::ROLE_ENGINEER_CIL]);
        $this->layout = '/main-fluid';
    }

    /**
     * @return array
     * @throws NotFoundHttpException
     */
    protected function getCfgParams(): array
    {
        $bookId = Yii::$app->getRequest()->get('bookId');
        if ($bookId === null) {
            return [];
        }
        /** @var PresentationBook $book */
        $book = PresentationBook::find()->ids($bookId)->one();
        if ($book === null) {
            throw new NotFoundHttpException("Records with ID {$bookId} not found.");
        }
        return [
            'bookId' => $book->id
        ];
    }
}
