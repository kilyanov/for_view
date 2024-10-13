<?php

declare(strict_types=1);

namespace kilyanov\repository\behaviors;

use kilyanov\repository\bus\RepositoryAttachmentCommand;
use kilyanov\repository\bus\RepositoryDetachmentCommand;
use League\Tactician\CommandBus;
use yii\base\Behavior;
use yii\base\UnknownPropertyException;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;

class RepositoryBehavior extends Behavior
{
    /**
     * @var string
     */
    public string $attribute;

    /**
     * @var string
     */
    public string $relation;

    /**
     * @var ActiveRecord
     */
    public $owner;

    /**
     * @var array
     */
    protected array $after = [];

    /**
     * @var bool
     */
    protected bool $afterDirty = false;

    /**
     * @var array
     */
    protected array $before = [];

    /**
     * @var bool
     */
    protected bool $beforeDirty = false;

    /**
     * @var CommandBus
     */
    protected CommandBus $bus;

    /**
     * RepositoryBehavior constructor.
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
            BaseActiveRecord::EVENT_AFTER_INSERT => [$this, 'afterInsert'],
            BaseActiveRecord::EVENT_AFTER_UPDATE => [$this, 'afterUpdate'],
            BaseActiveRecord::EVENT_AFTER_DELETE => [$this, 'afterDelete'],
        ];
    }

    /**
     * @return void
     */
    public function afterInsert(): void
    {
        $after = $this->getAfter();

        if (!empty($after)) {
            $this->link($after);
        }
    }

    /**
     * @return array
     */
    protected function getAfter(): array
    {
        return $this->after;
    }

    /**
     * @param array $repositoryIds
     */
    protected function link(array $repositoryIds): void
    {
        $model = $this->owner;
        $attribute = $this->attribute;
        $relation = $this->relation;

        $command = new RepositoryAttachmentCommand($model, $attribute, $relation, $repositoryIds);
        $this->bus->handle($command);

        $this->beforeDirty = false;
    }

    /**
     * @return void
     */
    public function afterUpdate(): void
    {
        $after = $this->getAfter();
        $before = $this->getBefore();

        $delete = array_diff($before, $after);
        if (!empty($delete)) {
            $this->unlink($delete);
        }

        $insert = array_diff($after, $before);
        if (!empty($insert)) {
            $this->link($insert);
        }
    }

    /**
     * @return array
     */
    protected function getBefore(): array
    {
        if ($this->beforeDirty === false) {
            $this->beforeDirty = true;

            $model = $this->owner;
            $relation = $this->relation;

            $this->before = $model->getRelation($relation)->column();
        }

        return $this->before;
    }

    /**
     * @param array $repositoryIds
     */
    protected function unlink(array $repositoryIds): void
    {
        $model = $this->owner;
        $relation = $this->relation;

        $command = new RepositoryDetachmentCommand($model, $relation, $repositoryIds);
        $this->bus->handle($command);

        $this->beforeDirty = false;
    }

    /**
     * @return void
     */
    public function afterDelete(): void
    {
        $before = $this->getBefore();

        if (!empty($before)) {
            $this->unlink($before);
        }
    }

    /**
     * @param string $name
     * @param bool $checkVars
     *
     * @return bool
     */
    public function canGetProperty($name, $checkVars = true): bool
    {
        if ($this->attribute === $name) {
            return true;
        }

        return parent::canGetProperty($name, $checkVars);
    }

    /**
     * @param string $name
     * @param bool $checkVars
     *
     * @return bool
     */
    public function canSetProperty($name, $checkVars = true): bool
    {
        if ($this->attribute === $name) {
            return true;
        }

        return parent::canSetProperty($name, $checkVars);
    }

    /**
     * @param string $name
     *
     * @return array|mixed
     * @throws UnknownPropertyException
     */
    public function __get($name)
    {
        if ($this->attribute === $name) {
            return $this->afterDirty === true ? $this->getAfter() : $this->getBefore();
        }

        return parent::__get($name);
    }

    /**
     * @param string $name
     * @param mixed $value
     *
     * @throws UnknownPropertyException
     */
    public function __set($name, $value)
    {
        if ($this->attribute === $name) {
            if (!is_array($value)) {
                $value = array_filter((array)$value);
            }

            $this->afterDirty = true;
            $this->after = $value;
        } else {
            parent::__set($name, $value);
        }
    }
}
