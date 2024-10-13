<?php

declare(strict_types=1);

namespace kilyanov\architect\entity;

use Closure;
use Yii;
use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;

abstract class GroupEntity extends ElementEntity
{
    /**
     * @var null|array|Closure
     */
    protected null|array|Closure $items = [];

    /**
     * @return string
     * @throws InvalidConfigException
     */
    public function make(): string
    {
        if (empty($this->getItems())) {
            throw new InvalidArgumentException("Property items not set.");
        }
        $items = [];
        $list = $this->getItems();

        if ($list instanceof Closure) {
            $list = call_user_func($list);
        }

        foreach ($list as $item) {
            if ($item instanceof ElementEntity) {
                $items[] = $item->make();
            }
            if (is_array($item)) {
                $entity = Yii::createObject($item);
                /** @var ElementEntity $entity */
                $items[] = $entity->make();
            }
            if (is_string($item)) {
                $items[] = $item;
            }
        }
        $this->setItems($items);

        return parent::make();
    }

    /**
     * @return array|Closure|null
     */
    public function getItems(): null|array|Closure
    {
        return $this->items;
    }

    /**
     * @param array|Closure|null $items
     * @return void
     */
    public function setItems(null|array|Closure $items = []): void
    {
        $this->items = $items;
    }
}
