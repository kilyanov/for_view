<?php

declare(strict_types=1);

namespace kilyanov\sortable\actions;

use Exception;
use Yii;
use yii\base\Action;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Class UpdateAllAction
 *
 * @package kilyanov\sortable\actions
 */
class UpdateAllAction extends Action
{
    /**
     * @var array
     */
    public array $items = [];

    /**
     * @var ActiveRecord
     */
    public ActiveRecord $model;

    /**
     * @var string
     */
    public string $positionAttribute = 'sort';

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

    /**
     * @return array
     */
    protected function loadData(): array
    {
        $class = $this->model;
        $models = $class::find()->where(['id' => $this->items])->all();
        return ArrayHelper::map(
            $models,
            'id',
            static function ($row) {
                return $row;
            }
        );
    }
}
