<?php

declare(strict_types=1);

namespace kilyanov\repository\bus;

use yii\db\ActiveRecord;

class RepositoryDetachmentCommand
{
    /**
     * @param ActiveRecord $model
     * @param string $relation
     * @param array $repositoryIds
     */
    public function __construct(
        protected ActiveRecord $model,
        protected string $relation,
        protected array $repositoryIds
    )
    {
    }

    /**
     * @return ActiveRecord
     */
    public function getModel(): ActiveRecord
    {
        return $this->model;
    }

    /**
     * @return string
     */
    public function getRelation(): string
    {
        return $this->relation;
    }

    /**
     * @return array
     */
    public function getRepositoryIds(): array
    {
        return $this->repositoryIds;
    }
}
