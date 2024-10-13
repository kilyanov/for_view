<?php

declare(strict_types=1);

namespace kilyanov\repository\bus;

use kilyanov\repository\models\Repository;
use League\Flysystem\FilesystemException;
use League\Flysystem\FilesystemOperator;
use yii\db\Exception;
use yii\db\StaleObjectException;

class RepositoryDetachmentHandler
{
    /**
     * @param FilesystemOperator $filesystem
     */
    public function __construct(
        protected FilesystemOperator $filesystem
    )
    {
    }

    /**
     * @param RepositoryDetachmentCommand $command
     * @return void
     * @throws Exception
     * @throws StaleObjectException
     * @throws FilesystemException
     */
    public function __invoke(RepositoryDetachmentCommand $command): void
    {
        $model = $command->getModel();
        $relation = $command->getRelation();
        $repositoryIds = $command->getRepositoryIds();

        $repositories = $this->getRepositories($repositoryIds);

        foreach ($repositories as $repository) {
            $model->unlink($relation, $repository, true);
            $this->filesystem->delete($repository->src);
        }
    }

    /**
     * @param array $repositoryIds
     * @return array
     */
    protected function getRepositories(array $repositoryIds): array
    {
        return Repository::find()->where([Repository::tableName() . '.[[id]]' => $repositoryIds])->all();
    }
}
