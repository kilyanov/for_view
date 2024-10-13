<?php

declare(strict_types=1);

namespace kilyanov\architect\factory;

use Exception;
use kilyanov\architect\Answer;
use kilyanov\architect\entity\ContentEntity;
use kilyanov\architect\entity\RowEntity;
use kilyanov\architect\interfaces\AnswerInterface;
use kilyanov\architect\interfaces\BaseAnswerFactoryInterface;
use kilyanov\architect\interfaces\ElementInterface;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;

class BaseAnswerFactory implements BaseAnswerFactoryInterface
{
    /**
     * @param array $config
     * @return AnswerInterface
     * @throws Exception
     */
    public static function create(array $config = []): AnswerInterface
    {
        $object = new Answer();
        $title = ArrayHelper::keyExists('config.title', $config);
        if ($title != null) {
            $object->setTitle((string)$title);
        }
        if (ArrayHelper::keyExists('config.content.class', $config)) {
            $object->setContent(ArrayHelper::getValue($config, 'config.content'));
        }
        else {
            $object->setContent(new ContentEntity());
        }
        if (ArrayHelper::keyExists('config.footer.class', $config)) {
            $object->setFooter(ArrayHelper::getValue($config, 'config.footer'));
        }
        else {
            $object->setFooter(new RowEntity());
        }

        return $object;
    }
}
