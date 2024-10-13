<?php

declare(strict_types=1);

namespace kilyanov\repository\bus;

use DateTime;
use kilyanov\repository\models\Repository;

class RepositoryAttachmentHandler
{
    /**
     * @param RepositoryAttachmentCommand $command
     */
    public function __invoke(RepositoryAttachmentCommand $command): void
    {
        $model = $command->getModel();
        $attribute = $command->getAttribute();
        $relation = $command->getRelation();
        $repositoryIds = $command->getRepositoryIds();

        $repositories = $this->getRepositories($repositoryIds);

        foreach ($repositories as $repository) {
            $model->link($relation, $repository, [
                'model' => get_class($model),
                'attribute' => $attribute,
                'createdAt' => (new DateTime())->format('Y-m-d H:i:s'),
                'updatedAt' => (new DateTime())->format('Y-m-d H:i:s'),
            ]);
        }
    }

    /**
     * @param array $repositoryIds
     *
     * @return array
     */
    protected function getRepositories(array $repositoryIds): array
    {
        return Repository::find()->where([Repository::tableName() . '.[[id]]' => $repositoryIds])->all();
    }
}
