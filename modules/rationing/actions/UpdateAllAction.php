<?php

declare(strict_types=1);

namespace app\modules\rationing\actions;

use Exception;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use kilyanov\sortable\actions\UpdateAllAction as UpdateAllActionAlias;

class UpdateAllAction extends UpdateAllActionAlias
{
    /**
     * @throws Exception
     */
    public function run(): void
    {
        $position = 0;
        if (!empty($this->items)) {
            try {
                $dataItems = $this->loadData();
                foreach ($this->items as $id) {
                    $model = ArrayHelper::getValue($dataItems, $id);
                    if ($model instanceof ActiveRecord) {
                        $position++;
                        $model->setAttribute($this->positionAttribute, $position);
                        $model->detachBehavior('RationingProductDataBehavior');
                        $model->detachBehavior('RationingDataBehavior');
                        if (!$model->save(false)) {;
                            throw new Exception('', (int)$model->getErrors());
                        }
                    }
                }
            }
            catch (Exception $exception) {
                Yii::error($exception->getMessage());
            }
        }
    }
}
