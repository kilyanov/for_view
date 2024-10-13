<?php

declare(strict_types=1);

namespace kilyanov\repository\actions;

use kilyanov\filesystem\bus\FilesystemGridBuilderCommand;
use kilyanov\filesystem\bus\FilesystemWriteStreamCommand;
use kilyanov\repository\bus\RepositoryCreateCommand;
use League\Tactician\CommandBus;
use yii\base\Action;
use yii\web\Response;
use yii\web\UploadedFile;

class DropzoneUploadingAction extends Action
{
    /**
     * @var UploadedFile|null
     */
    public ?UploadedFile $uploadedFile = null;

    /**
     * @var string
     */
    public string $uploadedDirectory = '/repository';

    /**
     * DropzoneUploadingAction constructor.
     *
     * @param $id
     * @param $controller
     * @param CommandBus $bus
     * @param array $config
     */
    public function __construct(
        $id,
        $controller,
        protected CommandBus $bus,
        array $config = []
    )
    {
        parent::__construct($id, $controller, $config);
    }

    /**
     * @return Response
     */
    public function run(): Response
    {
        if ($this->uploadedFile === null) {
            $this->uploadedFile = UploadedFile::getInstanceByName('file');
        }

        if ($this->uploadedFile instanceof UploadedFile) {
            $command = new FilesystemGridBuilderCommand(
                $this->uploadedFile->tempName,
                null,
                $this->uploadedFile->extension);
            $path = $this->bus->handle($command);

            $path = $this->uploadedDirectory . '/' . $path;

            $resource = fopen($this->uploadedFile->tempName, 'rb');

            $command = new FilesystemWriteStreamCommand(
                $path,
                $resource,
                [
                    'visibility' => 'public',
                    'directory_visibility' => 'public'
                ]
            );
            $this->bus->handle($command);

            fclose($resource);

            $command = new RepositoryCreateCommand(
                $this->uploadedFile->name,
                $path,
                [
                    'size' => $this->uploadedFile->size,
                    'mime' => $this->uploadedFile->type,
                    'extension' => $this->uploadedFile->extension,
                ]
            );

            if ($id = $this->bus->handle($command)) {
                return $this->controller->asJson([
                    'status' => 'success',
                    'repository' => [
                        'id' => $id,
                        'src' => $path,
                    ],
                ]);
            }
            return $this->controller->asJson([
                'status' => 'success',
                'repository' => [
                    'id' => 1,
                    'src' => $path,
                ],
            ]);
        }

        return $this->controller->asJson([
            'status' => 'error',
        ]);
    }
}
