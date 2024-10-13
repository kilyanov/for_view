<?php

declare(strict_types=1);

namespace kilyanov\architect\interfaces;

interface BaseAnswerFactoryInterface
{
    /**
     * @param array $config
     * @return AnswerInterface
     */
    public static function create(array $config = []): AnswerInterface;
}
