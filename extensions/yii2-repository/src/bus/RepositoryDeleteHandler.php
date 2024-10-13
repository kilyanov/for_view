<?php

declare(strict_types=1);

namespace kilyanov\repository\bus;

use kilyanov\repository\models\Repository;
use Throwable;
use yii\db\StaleObjectException;

class RepositoryDeleteHandler
{
    /**
     * @param RepositoryDeleteCommand $command
     * @return bool
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function __invoke(RepositoryDeleteCommand $command): bool
    {
        $id = $command->getId();

        return $this->delete($id);
    }

    /**
     * @param int $id
     * @return bool
     * @throws StaleObjectException
     * @throws Throwable
     */
    protected function delete(int $id): bool
    {
        return Repository::find()->where([Repository::tableName() . '.[[id]]' => $id])->one()->delete();
    }
}
