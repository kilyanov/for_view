<?php

declare(strict_types=1);

namespace app\common;

use yii\web\Request as BaseRequest;

class Request extends BaseRequest
{
    /**
     * @var string $web
     */
    public string $web;

    /**
     * @return string
     */
    public function getBaseUrl(): string
    {
        return str_replace($this->web, "", parent::getBaseUrl());
    }
}
