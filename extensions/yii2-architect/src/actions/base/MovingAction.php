<?php

declare(strict_types=1);

namespace kilyanov\architect\actions\base;

use kilyanov\architect\controller\ApplicationController;

class MovingAction extends IndexAction
{
    /**
     * @return void
     */
    public function init(): void
    {
        $this->setTemplate('moving');
        /** @var ApplicationController $controller */
        $controller = $this->controller;
        $controller->setCfgSearchModel(['pageLimit' => false]);
    }
}
