<?php

declare(strict_types=1);

namespace app\modules\nso\models\query;

use app\common\database\traits\DefaultActiveQueryTrait;
use app\common\database\traits\HiddenActiveQueryTrait;
use app\modules\nso\models\StandChart;
use yii\db\ActiveQuery;

class StandChartQuery extends ActiveQuery
{
    use DefaultActiveQueryTrait;
    use HiddenActiveQueryTrait;

    /**
     * {@inheritdoc}
     * @return StandChart[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return StandChart|array|null
     */
    public function one($db = null): array|StandChart|null
    {
        return parent::one($db);
    }

    /**
     * @param int|null $month
     * @return StandChartQuery
     */
    public function monthPlan(?int $month = null): StandChartQuery
    {
        return $this->andFilterWhere([StandChart::tableName() . '.[[monthPlan]]' => $month]);
    }

    /**
     * @param int|null $month
     * @return StandChartQuery
     */
    public function monthFact(?int $month = null): StandChartQuery
    {
        return $this->andFilterWhere([StandChart::tableName() . '.[[monthFact]]' => $month]);
    }
}
