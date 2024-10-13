<?php

declare(strict_types=1);

namespace app\modules\industry\models\query;

use app\common\database\traits\DefaultActiveQueryTrait;
use app\modules\industry\models\PresentationBook;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\app\modules\industry\models\PresentationBook]].
 *
 * @see PresentationBook
 */
class PresentationBookQuery extends ActiveQuery
{
    use DefaultActiveQueryTrait;

    /**
     * {@inheritdoc}
     * @return PresentationBook[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return PresentationBook|array|null
     */
    public function one($db = null): array|PresentationBook|null
    {
        return parent::one($db);
    }
}
