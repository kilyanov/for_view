<?php

declare(strict_types=1);

namespace app\modules\application\models\query;

use app\common\database\traits\DefaultActiveQueryTrait;
use app\common\database\traits\HiddenActiveQueryTrait;
use app\modules\application\models\Application;
use yii\db\ActiveQuery;

class ApplicationQuery extends ActiveQuery
{
    use DefaultActiveQueryTrait;
    use HiddenActiveQueryTrait;

    /**
     * {@inheritdoc}
     * @return Application[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Application|array|null
     */
    public function one($db = null): array|Application|null
    {
        return parent::one($db);
    }

    /**
     * @param string|null $orderId
     * @return self
     */
    public function orderId(?string $orderId): self
    {
        return $this->andFilterWhere([Application::tableName() . '.[[orderId]]' => $orderId]);
    }

    /**
     * @param string|null $productId
     * @return self
     */
    public function product(?string $productId): self
    {
        return $this->andFilterWhere([Application::tableName() . '.[[productId]]' => $productId]);
    }
}
