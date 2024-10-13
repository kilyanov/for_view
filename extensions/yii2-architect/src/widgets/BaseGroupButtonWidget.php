<?php

declare(strict_types=1);

namespace kilyanov\architect\widgets;

use yii\bootstrap5\Widget;

class BaseGroupButtonWidget extends Widget
{
    /**
     * @var bool
     */
    public bool $isAjax = true;

    /**
     * @var array
     */
    public array $access = [];

    /**
     * @var array
     */
    public array $visible = [];

    /**
     * @var array
     */
    public array $configUrl = [];

    /**
     * @var array
     */
    public array $exceptionConfigUrl = [];

    /**
     * @var string
     */
    public string $active = '';
}
