<?php

declare(strict_types=1);

namespace kilyanov\behaviors\common;

use yii\base\Behavior;
use yii\base\Event;
use yii\base\InvalidConfigException;
use yii\caching\CacheInterface;
use yii\caching\TagDependency;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\di\Instance;

class TagDependencyBehavior extends Behavior
{
    /**
     * @var string|CacheInterface
     */
    public $cache = 'cache';

    /**
     * @return array
     */
    public function events()
    {
        return [
            BaseActiveRecord::EVENT_AFTER_INSERT => 'invalidate',
            BaseActiveRecord::EVENT_AFTER_UPDATE => 'invalidate',
            BaseActiveRecord::EVENT_AFTER_DELETE => 'invalidate',
        ];
    }

    /**
     * @param Event $event
     *
     * @throws InvalidConfigException
     */
    public function invalidate(Event $event)
    {
        /** @var ActiveRecord $sender */
        $sender = $event->sender;

        /** @var CacheInterface $cache */
        $cache = Instance::ensure($this->cache, CacheInterface::class);

        $tags = [
            get_class($sender),
            $sender::tableName(),
        ];

        TagDependency::invalidate($cache, $tags);
    }
}
