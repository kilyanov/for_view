<?php

declare(strict_types=1);

namespace kilyanov\sortable\widgets;

use Yii;
use yii\base\Widget;
use yii\bootstrap5\Html;
use yii\helpers\ArrayHelper;

/**
 * Class LinkMovingWidget
 *
 * @package kilyanov\sortable
 */
class LinkMovingWidget extends Widget
{
    /**
     * @var array
     */
    public array $access = [];

    /**
     * @var string|null
     */
    public ?string $name = 'Режим перемещения';

    /**
     * @var array|string[]
     */
    public array $url = ['moving'];

    /**
     * @var string[]
     */
    public array $options = ['class' => 'btn btn-warning', 'target' => '_blank', 'data-pjax' => 0];

    /**
     * @return string
     */
    public function run(): string
    {
        return $this->isAccess() ?
            Html::a($this->name, ArrayHelper::merge($this->url, Yii::$app->request->queryParams), $this->options) : '';
    }

    /**
     * @return bool
     */
    public function isAccess(): bool
    {
        if (!empty($this->access)) {
            foreach ($this->access as $access) {
                if (!Yii::$app->user->can($access)) {
                    return false;
                }
            }
        }

        return true;
    }
}
