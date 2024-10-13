<?php

declare(strict_types=1);

namespace kilyanov\repository\bus;

use kilyanov\repository\models\Repository;

class RepositoryCreateHandler
{
    /**
     * @param RepositoryCreateCommand $command
     * @return int|string|null
     */
    public function __invoke(RepositoryCreateCommand $command): null|int|string
    {
        $title = $command->getTitle();
        $src = $command->getSrc();
        $meta = $command->getMeta();

        $model = $this->create($title, $src, $meta);

        if ($model instanceof Repository) {
            return $model->id;
        }

        return null;
    }

    /**
     * @param string $title
     * @param string $src
     * @param array $meta
     *
     * @return Repository|null
     */
    protected function create(string $title, string $src, array $meta): ?Repository
    {
        $model = new Repository([
            'title' => $title,
            'src' => $src,
            'meta' => $meta,
        ]);

        if ($model->save()) {
            return $model;
        }

        return null;
    }
}
