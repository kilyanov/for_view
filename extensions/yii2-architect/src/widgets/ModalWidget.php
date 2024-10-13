<?php

declare(strict_types=1);

namespace kilyanov\architect\widgets;

use kilyanov\architect\assets\ArchitectAsset;
use kilyanov\architect\interfaces\AnswerInterface;
use yii\base\InvalidConfigException;
use yii\bootstrap5\Modal;

class ModalWidget extends Modal
{
    /**
     * @var string
     */
    public $title = '';

    /**
     * @var string
     */
    public $size = Modal::SIZE_EXTRA_LARGE;

    /**
     * @var bool
     */
     public $closeButton = false;

    /**
     * @var string
     */
    public $footer = '';

    /**
     * @return void
     * @throws InvalidConfigException
     */
    public function init(): void
    {
        $this->setId(AnswerInterface::MODAL_ID);
        $this->registerAsset();
        parent::init();
    }

    /**
     * @return void
     */
    protected function registerAsset(): void
    {
        ArchitectAsset::register($this->getView());
    }
}
