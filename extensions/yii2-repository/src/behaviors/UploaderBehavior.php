<?php

declare(strict_types=1);

namespace kilyanov\repository\behaviors;

use kilyanov\filesystem\bus\FilesystemGridBuilderCommand;
use kilyanov\filesystem\bus\FilesystemWriteStreamCommand;
use kilyanov\repository\bus\RepositoryCreateCommand;
use League\Tactician\CommandBus;
use yii\base\Behavior;
use yii\base\Event;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\web\UploadedFile;

class UploaderBehavior extends Behavior
{
    /**
     * @var string
     */
    public string $uploadedFileAttribute;

    /**
     * @var string
     */
    public string $repositoryAttribute;

    /**
     * @var bool
     */
    public bool $multiple = false;

    /**
     * @var string
     */
    public string $uploadedDirectory = '/repository';

    /**
     * @var CommandBus
     */
    protected CommandBus $bus;

    /**
     * UploaderBehavior constructor.
     *
     * @param CommandBus $bus
     * @param array $config
     */
    public function __construct(CommandBus $bus, array $config = [])
    {
        parent::__construct($config);

        $this->bus = $bus;
    }

    /**
     * @return array
     */
    public function events(): array
    {
        return [
            Model::EVENT_BEFORE_VALIDATE => [$this, 'beforeValidate'],
            BaseActiveRecord::EVENT_AFTER_INSERT => [$this, 'afterInsert'],
            BaseActiveRecord::EVENT_AFTER_UPDATE => [$this, 'afterUpdate'],
        ];
    }

    /**
     * @param Event $event
     */
    public function beforeValidate(Event $event): void
    {
        /** @var ActiveRecord $model */
        $model = $event->sender;

        if ($this->multiple === true) {
            $model->{$this->uploadedFileAttribute} = UploadedFile::getInstances($model, $this->uploadedFileAttribute);
        } else {
            $model->{$this->uploadedFileAttribute} = UploadedFile::getInstance($model, $this->uploadedFileAttribute);
        }
    }

    /**
     * @param Event $event
     */
    public function afterInsert(Event $event): void
    {
        $this->uploader($event);
    }

    /**
     * @param Event $event
     */
    protected function uploader(Event $event): void
    {
        /** @var ActiveRecord $model */
        $model = $event->sender;

        if ($this->multiple === true) {
            $uploadedFiles = $model->{$this->uploadedFileAttribute};
        } else {
            $uploadedFiles = [
                $model->{$this->uploadedFileAttribute},
            ];
        }

        $ids = [];

        foreach ($uploadedFiles as $uploadedFile) {
            if ($uploadedFile instanceof UploadedFile) {
                $command = new FilesystemGridBuilderCommand($uploadedFile->tempName, null, $uploadedFile->extension);
                $path = $this->bus->handle($command);

                $path = $this->uploadedDirectory . '/' . $path;

                $resource = fopen($uploadedFile->tempName, 'rb');

                $command = new FilesystemWriteStreamCommand($path, $resource);
                $this->bus->handle($command);

                fclose($resource);

                $command = new RepositoryCreateCommand($uploadedFile->name, $path, [
                    'size' => $uploadedFile->size,
                    'mime' => $uploadedFile->type,
                    'extension' => $uploadedFile->extension,
                ]);
                if ($id = $this->bus->handle($command)) {
                    $ids[] = $id;
                }
            }
        }

        if (!empty($ids)) {
            $model->{$this->repositoryAttribute} = $ids;
        }
    }

    /**
     * @param Event $event
     */
    public function afterUpdate(Event $event): void
    {
        $this->uploader($event);
    }
}
